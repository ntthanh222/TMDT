<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');
        $rating = $request->query('rating');

        $query = Review::with(['product', 'user'])->latest();

        if ($status === 'pending') {
            $query->where('is_approved', false);
        } elseif ($status === 'approved') {
            $query->where('is_approved', true);
        }

        if ($rating && in_array((int) $rating, [1, 2, 3, 4, 5], true)) {
            $query->where('rating', (int) $rating);
        }

        $reviews = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Review::count(),
            'pending' => Review::where('is_approved', false)->count(),
            'approved' => Review::where('is_approved', true)->count(),
        ];

        return view('admin.reviews.index', compact('reviews', 'status', 'rating', 'counts'));
    }

    /**
     * Display the specified review.
     */
    public function show(Review $review): View
    {
        $review->load(['product', 'user']);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve the specified review.
     */
    public function approve(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Đã duyệt hiển thị đánh giá thành công.');
    }

    /**
     * Hide the specified review.
     */
    public function hide(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => false]);

        return back()->with('success', 'Đã ẩn đánh giá khỏi hiển thị công khai.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Đã xóa đánh giá thành công.');
    }
}
