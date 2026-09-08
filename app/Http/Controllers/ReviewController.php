<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display the reviews for a given product.
     */
    public function index(Request $request, Product $product): View
    {
        $approvedReviews = $product->reviews()
            ->with('user')
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        $totalApproved = $product->reviews()->where('is_approved', true)->count();
        $averageRating = $totalApproved > 0
            ? round((float) $product->reviews()->where('is_approved', true)->avg('rating'), 1)
            : 0;

        $ratingCounts = [
            5 => $product->reviews()->where('is_approved', true)->where('rating', 5)->count(),
            4 => $product->reviews()->where('is_approved', true)->where('rating', 4)->count(),
            3 => $product->reviews()->where('is_approved', true)->where('rating', 3)->count(),
            2 => $product->reviews()->where('is_approved', true)->where('rating', 2)->count(),
            1 => $product->reviews()->where('is_approved', true)->where('rating', 1)->count(),
        ];

        $userReview = null;
        if ($request->user()) {
            $userReview = $product->reviews()
                ->where('user_id', $request->user()->id)
                ->first();
        }

        return view('reviews.index', compact(
            'product',
            'approvedReviews',
            'totalApproved',
            'averageRating',
            'ratingCounts',
            'userReview'
        ));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(StoreReviewRequest $request, Product $product): RedirectResponse
    {
        $userId = $request->user()->id;

        // Prevent duplicate review for the same product by the same user
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReview) {
            return redirect()
                ->route('reviews.index', $product)
                ->with('error', 'Bạn đã đánh giá sản phẩm này rồi. Bạn có thể chỉnh sửa đánh giá hiện tại.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
            'is_approved' => false,
        ]);

        return redirect()
            ->route('reviews.index', $product)
            ->with('success', 'Đánh giá của bạn đã được gửi thành công và đang chờ ban quản trị kiểm duyệt.');
    }

    /**
     * Update the specified review in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review): RedirectResponse
    {
        if ($request->user()->id !== $review->user_id) {
            abort(403, 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }

        $review->update([
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
            'is_approved' => false, // Set to pending approval after edit
        ]);

        return redirect()
            ->route('reviews.index', $review->product_id)
            ->with('success', 'Đánh giá đã được cập nhật thành công và đang chờ ban quản trị duyệt lại.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Request $request, Review $review): RedirectResponse
    {
        if ($request->user()->id !== $review->user_id) {
            abort(403, 'Bạn không có quyền xóa đánh giá này.');
        }

        $productId = $review->product_id;
        $review->delete();

        return redirect()
            ->route('reviews.index', $productId)
            ->with('success', 'Đánh giá của bạn đã được xóa.');
    }
}
