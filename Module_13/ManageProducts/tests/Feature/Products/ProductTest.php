<?php

namespace Tests\Feature\Products;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders(['Accept' => 'application/json']);
    }

    public function test_products_can_be_indexed(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_product_can_be_shown(): void
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $product->id,
                'name' => $product->name,
            ]);
    }

    public function test_product_can_be_stored(): void
    {
        $productData = [
            'sku' => 'TEST-SKU-123',
            'name' => 'Test Product',
            'price' => 99.99,
        ];

        $response = $this->postJson('/api/products', $productData);

        $response->assertStatus(201)
            ->assertJsonFragment($productData);

        $this->assertDatabaseHas('products', $productData);
    }

    public function test_product_can_be_updated(): void
    {
        $product = Product::factory()->create();

        $updateData = [
            'name' => 'Updated Product Name',
            'price' => 149.50,
        ];

        $response = $this->putJson("/api/products/{$product->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product Name',
        ]);
    }

    public function test_product_can_be_destroyed(): void
    {
        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
