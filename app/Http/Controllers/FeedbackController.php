<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Show the form for creating a new feedback/contact message.
     */
    public function create(): View
    {
        $recentReviews = Review::with(['product', 'user'])
            ->where('is_approved', true)
            ->latest()
            ->limit(3)
            ->get();

        if ($recentReviews->isEmpty()) {
            $recentReviews = collect([
                [
                    'user_name' => 'Nguyễn Thị A',
                    'rating' => 5,
                    'comment' => 'Cà phê rất thơm, phục vụ nhanh và không gian đẹp, mình sẽ quay lại thêm lần nữa.',
                    'created_at' => now()->subDays(2),
                    'product_name' => 'Cà phê Arabica',
                ],
                [
                    'user_name' => 'Trần Văn B',
                    'rating' => 5,
                    'comment' => 'Hương vị cân bằng, quán sạch sẽ và nhân viên nhiệt tình. Rất đáng tiền.',
                    'created_at' => now()->subDays(4),
                    'product_name' => 'Cold Brew',
                ],
                [
                    'user_name' => 'Lê Thị C',
                    'rating' => 4,
                    'comment' => 'Sản phẩm ổn, giao hàng đúng giờ và đóng gói cẩn thận.',
                    'created_at' => now()->subDays(6),
                    'product_name' => 'Bánh mì sữa nóng',
                ],
            ]);
        }

        return view('feedback.contact', compact('recentReviews'));
    }

    /**
     * Store a newly created feedback/contact message in storage.
     */
    public function store(StoreFeedbackRequest $request): RedirectResponse
    {
        Feedback::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'subject' => $request->validated('subject'),
            'message' => $request->validated('message'),
            'is_read' => false,
        ]);

        return redirect()
            ->route('contact.create')
            ->with('success', 'Cảm ơn bạn đã gửi ý kiến phản hồi / liên hệ. Chúng tôi sẽ phản hồi trong thời gian sớm nhất!');
    }

    public function storeReview(Request $request): RedirectResponse
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.integer' => 'Số sao đánh giá không hợp lệ.',
            'rating.min' => 'Số sao đánh giá tối thiểu là 1.',
            'rating.max' => 'Số sao đánh giá tối đa là 5.',
            'comment.required' => 'Vui lòng để lại nhận xét của bạn.',
            'comment.max' => 'Nhận xét không được vượt quá 1000 ký tự.',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để gửi đánh giá.');
        }

        $product = Product::query()->first();

        if (!$product) {
            return redirect()->route('contact.create')->with('error', 'Hiện chưa có sản phẩm nào để đánh giá.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
            'is_approved' => false,
        ]);

        return redirect()->route('contact.create')->with('success', 'Đánh giá của bạn đã được gửi và đang chờ quản trị duyệt.');
    }
}
