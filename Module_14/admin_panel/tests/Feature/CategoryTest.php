<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест на создание категории.
     *
     * @return void
     */
    public function test_can_create_category()
    {
        $category = Category::factory()->create([
            'name' => 'Тестовая категория',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Тестовая категория',
        ]);

        $this->assertEquals('Тестовая категория', $category->name);
    }

    /**
     * Тест для проверки связи "один ко многим" с продуктами.
     *
     * @return void
     */
    public function test_category_has_many_products()
    {
        $category = Category::factory()->create();
        Product::factory()->count(3)->create([
            'category_id' => $category->id,
        ]);

        $this->assertInstanceOf(Product::class, $category->products->first());
        $this->assertCount(3, $category->products);
    }
}
