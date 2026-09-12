<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
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

    public function test_authenticated_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::first();

        $response = $this->actingAs($user)
            ->post(route('cart.add', $product), [
                'quantity' => 2,
            ]);

        $response->assertRedirect(route('products.search'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 99000.00,
        ]);
    }

    public function test_authenticated_user_can_update_cart_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::first();

        $this->actingAs($user)->post(route('cart.add', $product), ['quantity' => 1]);

        $item = $user->fresh()->cart->items()->first();

        $response = $this->actingAs($user)
            ->patch(route('cart.update', $item), [
                'quantity' => 5,
            ]);

        $response->assertRedirect(route('cart.index'))
            ->assertSessionHas('success');

        $this->assertSame(5, $item->fresh()->quantity);
    }

    public function test_authenticated_user_can_remove_item_from_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::first();

        $this->actingAs($user)->post(route('cart.add', $product), ['quantity' => 1]);

        $item = $user->fresh()->cart->items()->first();

        $response = $this->actingAs($user)
            ->delete(route('cart.remove', $item));

        $response->assertRedirect(route('cart.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseCount('cart_items', 0);
    }
}
