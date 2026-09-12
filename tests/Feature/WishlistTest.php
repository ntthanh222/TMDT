<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name' => 'Cà phê mẫu',
            'slug' => 'ca-phe-mau',
        ]);

        Product::create([
            'category_id' => $category->id,
            'name' => 'Cà phê Arabica',
            'slug' => 'ca-phe-arabica',
            'price' => 120000,
            'sale_price' => 99000,
            'stock_quantity' => 20,
            'sku' => 'CF-001',
            'is_active' => true,
        ]);
    }

    public function test_authenticated_user_can_add_product_to_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::first();

        $response = $this->actingAs($user)
            ->post(route('wishlist.add', $product));

        $response->assertRedirect(route('products.search'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_authenticated_user_can_remove_product_from_wishlist(): void
    {
        $user = User::factory()->create();
        $product = Product::first();

        $this->actingAs($user)->post(route('wishlist.add', $product));

        $response = $this->actingAs($user)
            ->delete(route('wishlist.remove', $product));

        $response->assertRedirect(route('wishlist.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('wishlists', 0);
    }
}
