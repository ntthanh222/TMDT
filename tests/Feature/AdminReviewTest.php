<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $category = Category::create([
            'name' => 'Trà',
            'slug' => 'tra',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Trà Oolong Thượng Hạng',
            'slug' => 'tra-oolong-thuong-hang',
            'price' => 150000,
            'stock_quantity' => 20,
            'sku' => 'TRA001',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_admin_reviews(): void
    {
        $response = $this->get(route('admin.reviews.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_admin_reviews(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('admin.reviews.index'));

        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_reviews_list(): void
    {
        Review::create([
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
            'rating' => 4,
            'comment' => 'Trà vị thanh ngọt',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.reviews.index'));

        $response->assertOk()
            ->assertSee('Quản Lý Đánh Giá Sản Phẩm')
            ->assertSee('Trà Oolong Thượng Hạng')
            ->assertSee('Trà vị thanh ngọt');
    }

    public function test_admin_can_view_review_detail(): void
    {
        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
            'rating' => 5,
            'comment' => 'Chi tiết đánh giá của khách hàng',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.reviews.show', $review));

        $response->assertOk()
            ->assertSee("Chi tiết đánh giá #{$review->id}")
            ->assertSee('Chi tiết đánh giá của khách hàng')
            ->assertSee($this->customer->name);
    }

    public function test_admin_can_approve_review(): void
    {
        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
            'rating' => 5,
            'comment' => 'Chờ được duyệt',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.reviews.approve', $review));

        $response->assertRedirect()
            ->assertSessionHas('success');

        $review->refresh();
        $this->assertTrue($review->is_approved);
    }

    public function test_admin_can_hide_review(): void
    {
        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
            'rating' => 1,
            'comment' => 'Review vi phạm tiêu chuẩn cộng đồng',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.reviews.hide', $review));

        $response->assertRedirect()
            ->assertSessionHas('success');

        $review->refresh();
        $this->assertFalse($review->is_approved);
    }

    public function test_admin_can_delete_review(): void
    {
        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => $this->customer->id,
            'rating' => 1,
            'comment' => 'Review spam cần xóa bỏ',
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.reviews.destroy', $review));

        $response->assertRedirect(route('admin.reviews.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}
