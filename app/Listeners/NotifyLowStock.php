<?php

namespace App\Listeners;

use App\Events\StockThresholdReached;
use Illuminate\Support\Facades\Log;

class NotifyLowStock
{
    public function handle(StockThresholdReached $event): void
    {
        Log::warning('Low stock alert', [
            'product_id' => $event->product->id,
            'sku' => $event->product->sku,
            'name' => $event->product->name,
            'stock' => $event->product->stock_quantity,
            'threshold' => $event->product->low_stock_threshold,
        ]);
    }
}
