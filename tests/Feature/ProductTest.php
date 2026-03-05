<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products(): void
    {
        Product::factory()->count(5)->create();
        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'data', 'pagination']);
    }

    public function test_can_create_product(): void
    {
        $response = $this->postJson('/api/products', [
            'sku' => 'PRD-0001-AB',
            'name' => 'Test Product',
            'price' => 99.99,
            'stock_quantity' => 50,
        ]);
        $response->assertStatus(201)
            ->assertJsonPath('data.sku', 'PRD-0001-AB');
        $this->assertDatabaseHas('products', ['sku' => 'PRD-0001-AB']);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create();
        $response = $this->putJson("/api/products/{$product->id}", ['name' => 'Updated Name']);
        $response->assertStatus(200)
            ->assertJsonPath('data.name', 'Updated Name');
    }

    public function test_can_soft_delete_product(): void
    {
        $product = Product::factory()->create();
        $this->deleteJson("/api/products/{$product->id}")->assertStatus(200);
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_stock_cannot_go_negative(): void
    {
        $product = Product::factory()->create(['stock_quantity' => 5]);
        $response = $this->postJson("/api/products/{$product->id}/stock", [
            'action' => 'decrement',
            'quantity' => 10,
        ]);
        $response->assertStatus(422);
    }

    public function test_can_list_low_stock_products(): void
    {
        Product::factory()->create(['stock_quantity' => 3, 'low_stock_threshold' => 10]);
        Product::factory()->create(['stock_quantity' => 50, 'low_stock_threshold' => 10]);
        $response = $this->getJson('/api/products/low-stock');
        $response->assertStatus(200)
            ->assertJsonPath('meta.total', 1);
    }
}
