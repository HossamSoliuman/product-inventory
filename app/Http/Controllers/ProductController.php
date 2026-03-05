<?php

namespace App\Http\Controllers;

use App\Events\StockThresholdReached;
use App\Http\Requests\AdjustStockRequest;
use App\Http\Requests\ListProductsRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Hossam\Licht\Controllers\LichtBaseController;
use Illuminate\Support\Facades\Cache;

class ProductController extends LichtBaseController
{
    use ApiResponse;

    public function index(ListProductsRequest $request)
    {
        $perPage = $request->integer('per_page', 10);
        $page = $request->integer('page', 1);
        $products = Cache::remember("products.page.{$page}.per.{$perPage}", config('cache.product_ttl', 60), function () use ($perPage) {
            return Product::paginate($perPage);
        });
        return $this->apiResponse(
            data: ProductResource::collection($products),
            pagination: $products
        );
    }

    public function show(Product $product)
    {
        return $this->apiResponse(ProductResource::make($product));
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        $this->flushProductCache();
        return $this->apiResponse(ProductResource::make($product), code: 201);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());
        $this->flushProductCache();
        return $this->apiResponse(ProductResource::make($product->fresh()));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        $this->flushProductCache();
        return $this->apiResponse(data: null, meta: 'Product deleted.');
    }

    public function adjustStock(AdjustStockRequest $request, Product $product)
    {
        if ($request->input('action') === 'decrement') {
            if ($product->stock_quantity - $request->integer('quantity') < 0) {
                return $this->apiResponse(data: null, success: 0, code: 422, meta: 'Stock quantity cannot go below zero.');
            }
            $product->decrement('stock_quantity', $request->integer('quantity'));
        } else {
            $product->increment('stock_quantity', $request->integer('quantity'));
        }
        $product->refresh();
        if ($product->stock_quantity <= $product->low_stock_threshold) {
            event(new StockThresholdReached($product));
        }

        return $this->apiResponse(data: ProductResource::make($product));
    }

    public function lowStock()
    {
        $products = Product::whereColumn('stock_quantity', '<=', 'low_stock_threshold')->get();
        return $this->apiResponse(
            data: ProductResource::collection($products),
            meta: ['total' => $products->count()]
        );
    }

    private function flushProductCache()
    {
        Cache::flush();
    }
}
