<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->numberBetween(50000, 500000);
        $salePrice = fake()->optional(0.6)->numberBetween(30000, $price - 1000);

        return [
            'category_id' => Category::query()->inRandomOrder()->value('id') ?? Category::factory()->create()->id,
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'description' => fake()->sentence(12),
            'price' => $price,
            'sale_price' => $salePrice,
            'stock_quantity' => fake()->numberBetween(0, 200),
            'sku' => fake()->unique()->bothify('SKU-###??'),
            'image' => null,
            'is_featured' => fake()->boolean(30),
            'is_active' => true,
            'view_count' => 0,
        ];
    }
}
