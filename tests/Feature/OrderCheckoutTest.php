<?php

namespace Tests\Feature;

use App\Models\CartModel;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_order_from_cart(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $category = Category::create([
            'name' => 'Cà phê',
            'slug' => 'ca-phe',
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cà phê Arabica',
            'slug' => 'ca-phe-arabica',
            'price' => 120000,
            'sale_price' => 110000,
            'stock_quantity' => 10,
            'sku' => 'CF-001',
            'is_active' => true,
        ]);

        $cart = CartModel::create(['user_id' => $user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 110000,
        ]);

        $response = $this->actingAs($user)->post(route('orders.checkout'), [
            'recipient_name' => 'Nguyễn Văn A',
            'phone' => '0909123456',
            'province' => 'Hà Nội',
            'district' => 'Ba Đình',
            'ward' => 'Ngọc Khánh',
            'address_line' => '123 Láng Hạ',
            'note' => 'Giao giờ hành chính',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('order_details', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->assertEquals(8, $product->fresh()->stock_quantity);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_admin_can_view_and_update_order_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Trà', 'slug' => 'tra']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Trà Sữa',
            'slug' => 'tra-sua',
            'price' => 50000,
            'stock_quantity' => 20,
            'sku' => 'TS-001',
            'is_active' => true,
        ]);

        $order = \App\Models\Order::create([
            'order_code' => 'ORD-TEST-001',
            'user_id' => $customer->id,
            'subtotal' => 100000,
            'shipping_fee' => 0,
            'total' => 100000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        \App\Models\OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 50000,
            'quantity' => 2,
            'subtotal' => 100000,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));
        $response->assertOk()->assertSee('Quản Lý Đơn Hàng');

        $update = $this->actingAs($admin)->patch(route('admin.orders.updateStatus', $order), [
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        $update->assertRedirect(route('admin.orders.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);
    }

    public function test_customer_can_view_order_history(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Cà phê', 'slug' => 'ca-phe']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cà phê Robusta',
            'slug' => 'ca-phe-robusta',
            'price' => 90000,
            'stock_quantity' => 5,
            'sku' => 'CF-002',
            'is_active' => true,
        ]);

        $order = \App\Models\Order::create([
            'order_code' => 'ORD-HISTORY-001',
            'user_id' => $user->id,
            'subtotal' => 180000,
            'shipping_fee' => 0,
            'total' => 180000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        \App\Models\OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 90000,
            'quantity' => 2,
            'subtotal' => 180000,
        ]);

        $response = $this->actingAs($user)->get(route('orders.history'));

        $response->assertOk()
            ->assertSee('Lịch sử đơn hàng')
            ->assertSee('ORD-HISTORY-001')
            ->assertSee('Cà phê Robusta');
    }

    public function test_customer_can_view_order_detail_and_cancel_pending_order(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Bánh', 'slug' => 'banh']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Bánh Muffin',
            'slug' => 'banh-muffin',
            'price' => 60000,
            'stock_quantity' => 10,
            'sku' => 'BH-003',
            'is_active' => true,
        ]);

        $order = \App\Models\Order::create([
            'order_code' => 'ORD-DETAIL-001',
            'user_id' => $user->id,
            'subtotal' => 120000,
            'shipping_fee' => 0,
            'total' => 120000,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        \App\Models\OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 60000,
            'quantity' => 2,
            'subtotal' => 120000,
        ]);

        $detailResponse = $this->actingAs($user)->get(route('orders.show', $order));
        $detailResponse->assertOk()->assertSee('Chi tiết đơn hàng')->assertSee('ORD-DETAIL-001')->assertSee('Bánh Muffin');

        $cancelResponse = $this->actingAs($user)->patch(route('orders.cancel', $order));
        $cancelResponse->assertRedirect(route('orders.history'))->assertSessionHas('success');

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);
    }

    public function test_profile_page_shows_recent_order_history(): void
    {
        $user = User::factory()->create(['role' => 'customer']);
        $category = Category::create(['name' => 'Nước ép', 'slug' => 'nuoc-ep']);
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Nước ép cam',
            'slug' => 'nuoc-ep-cam',
            'price' => 45000,
            'stock_quantity' => 12,
            'sku' => 'NE-010',
            'is_active' => true,
        ]);

        $order = \App\Models\Order::create([
            'order_code' => 'ORD-PROFILE-001',
            'user_id' => $user->id,
            'subtotal' => 90000,
            'shipping_fee' => 0,
            'total' => 90000,
            'status' => 'confirmed',
            'payment_status' => 'paid',
        ]);

        \App\Models\OrderDetail::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 45000,
            'quantity' => 2,
            'subtotal' => 90000,
        ]);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk()->assertSee('Lịch sử đơn hàng')->assertSee('ORD-PROFILE-001');
    }
}
