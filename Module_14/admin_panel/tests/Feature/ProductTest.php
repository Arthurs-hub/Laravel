<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест на создание продукта.
     *
     * @return void
     */
    public function test_can_create_product()
    {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'name' => 'Тестовый продукт',
            'sku' => 'TEST-001',
            'category_id' => $category->id,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Тестовый продукт',
            'sku' => 'TEST-001',
            'category_id' => $category->id,
        ]);

        $this->assertEquals('Тестовый продукт', $product->name);
        $this->assertEquals('TEST-001', $product->sku);
    }

    /**
     * Тест для проверки связи "принадлежит к" с категорией.
     *
     * @return void
     */
    public function test_product_belongs_to_category()
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(Category::class, $product->category);
        $this->assertEquals($category->id, $product->category->id);
    }
}
