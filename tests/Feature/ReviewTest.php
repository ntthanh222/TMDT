<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name' => 'Cà phê mẫu',
            'slug' => 'ca-phe-mau',
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Cà phê Robusta Nguyên Chất',
            'slug' => 'ca-phe-robusta-nguyen-chat',
            'price' => 120000,
            'stock_quantity' => 50,
            'sku' => 'CF001',
            'is_active' => true,
        ]);
    }

    public function test_guest_can_view_reviews_page(): void
    {
        Review::create([
            'product_id' => $this->product->id,
            'user_id' => User::factory()->create()->id,
            'rating' => 5,
            'comment' => 'Cà phê thơm ngon đậm đà',
            'is_approved' => true,
        ]);

        $response = $this->get(route('reviews.index', $this->product));

        $response->assertOk()
            ->assertSee('Cà phê Robusta Nguyên Chất')
            ->assertSee('Cà phê thơm ngon đậm đà')
            ->assertSee('Đăng nhập để đánh giá');
    }

    public function test_guest_cannot_submit_review(): void
    {
        $response = $this->post(route('reviews.store', $this->product), [
            'rating' => 5,
            'comment' => 'Rất tốt',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_authenticated_user_can_submit_review(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $this->product), [
                'rating' => 5,
                'comment' => 'Sản phẩm tuyệt vời, giao hàng nhanh',
            ]);

        $response->assertRedirect(route('reviews.index', $this->product))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('reviews', [
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Sản phẩm tuyệt vời, giao hàng nhanh',
            'is_approved' => false, // New reviews default to pending
        ]);
    }

    public function test_rating_1_is_valid(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $this->product), [
                'rating' => 1,
                'comment' => 'Hương vị không hợp',
            ]);

        $response->assertRedirect(route('reviews.index', $this->product));
        $this->assertDatabaseHas('reviews', [
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 1,
        ]);
    }

    public function test_rating_5_is_valid(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $this->product), [
                'rating' => 5,
                'comment' => 'Rất hài lòng',
            ]);

        $response->assertRedirect(route('reviews.index', $this->product));
        $this->assertDatabaseHas('reviews', [
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 5,
        ]);
    }

    public function test_rating_0_is_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $this->product), [
                'rating' => 0,
                'comment' => 'Rating không hợp lệ',
            ]);

        $response->assertSessionHasErrors('rating');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_rating_6_is_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $this->product), [
                'rating' => 6,
                'comment' => 'Rating vượt quá mức',
            ]);

        $response->assertSessionHasErrors('rating');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_comment_too_long_is_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $this->product), [
                'rating' => 5,
                'comment' => str_repeat('a', 1001),
            ]);

        $response->assertSessionHasErrors('comment');
        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_user_cannot_create_duplicate_review_for_same_product(): void
    {
        $user = User::factory()->create();

        Review::create([
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'Đánh giá ban đầu',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($user)
            ->post(route('reviews.store', $this->product), [
                'rating' => 5,
                'comment' => 'Đánh giá lần hai cố ý spam',
            ]);

        $response->assertRedirect(route('reviews.index', $this->product))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('reviews', 1);
        $this->assertDatabaseHas('reviews', [
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'Đánh giá ban đầu',
        ]);
    }

    public function test_user_can_update_own_review(): void
    {
        $user = User::factory()->create();

        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 3,
            'comment' => 'Bình thường',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($user)
            ->patch(route('reviews.update', $review), [
                'rating' => 5,
                'comment' => 'Dùng lâu thấy rất thích, đổi thành 5 sao',
            ]);

        $response->assertRedirect(route('reviews.index', $this->product))
            ->assertSessionHas('success');

        $review->refresh();
        $this->assertSame(5, $review->rating);
        $this->assertSame('Dùng lâu thấy rất thích, đổi thành 5 sao', $review->comment);
        $this->assertFalse($review->is_approved); // Pending re-approval
    }

    public function test_user_cannot_update_others_review(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => $owner->id,
            'rating' => 4,
            'comment' => 'Đánh giá của chủ sở hữu',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($otherUser)
            ->patch(route('reviews.update', $review), [
                'rating' => 1,
                'comment' => 'Cố ý phá hoại',
            ]);

        $response->assertForbidden();

        $review->refresh();
        $this->assertSame(4, $review->rating);
        $this->assertSame('Đánh giá của chủ sở hữu', $review->comment);
    }

    public function test_user_can_delete_own_review(): void
    {
        $user = User::factory()->create();

        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'Đánh giá muốn xóa',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($user)
            ->delete(route('reviews.destroy', $review));

        $response->assertRedirect(route('reviews.index', $this->product))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    public function test_user_cannot_delete_others_review(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $review = Review::create([
            'product_id' => $this->product->id,
            'user_id' => $owner->id,
            'rating' => 4,
            'comment' => 'Đánh giá của chủ sở hữu',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($otherUser)
            ->delete(route('reviews.destroy', $review));

        $response->assertForbidden();
        $this->assertDatabaseHas('reviews', ['id' => $review->id]);
    }

    public function test_unapproved_reviews_do_not_appear_publicly(): void
    {
        $user = User::factory()->create();

        Review::create([
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Nội dung chưa được duyệt',
            'is_approved' => false,
        ]);

        $response = $this->get(route('reviews.index', $this->product));

        $response->assertOk()
            ->assertDontSee('Nội dung chưa được duyệt')
            ->assertSee('0 đánh giá đã duyệt');
    }

    public function test_approved_reviews_appear_publicly(): void
    {
        $user = User::factory()->create();

        Review::create([
            'product_id' => $this->product->id,
            'user_id' => $user->id,
            'rating' => 5,
            'comment' => 'Nội dung đã được duyệt công khai',
            'is_approved' => true,
        ]);

        $response = $this->get(route('reviews.index', $this->product));

        $response->assertOk()
            ->assertSee('Nội dung đã được duyệt công khai')
            ->assertSee('1 đánh giá đã duyệt');
    }

    public function test_average_rating_only_includes_approved_reviews(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        // Approved reviews: 4 + 5 = 9 / 2 = 4.5
        Review::create([
            'product_id' => $this->product->id,
            'user_id' => $user1->id,
            'rating' => 4,
            'comment' => 'Đã duyệt 4 sao',
            'is_approved' => true,
        ]);

        Review::create([
            'product_id' => $this->product->id,
            'user_id' => $user2->id,
            'rating' => 5,
            'comment' => 'Đã duyệt 5 sao',
            'is_approved' => true,
        ]);

        // Unapproved review: 1 star, should NOT drag average down to (4+5+1)/3 = 3.3
        Review::create([
            'product_id' => $this->product->id,
            'user_id' => $user3->id,
            'rating' => 1,
            'comment' => 'Chưa duyệt 1 sao',
            'is_approved' => false,
        ]);

        $response = $this->get(route('reviews.index', $this->product));

        $response->assertOk()
            ->assertSee('4.5')
            ->assertSee('2 đánh giá đã duyệt');
    }
}
