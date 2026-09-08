<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the feedbacks.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status', 'all');

        $query = Feedback::latest();

        if ($status === 'unread') {
            $query->where('is_read', false);
        } elseif ($status === 'read') {
            $query->where('is_read', true);
        }

        $feedbacks = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => Feedback::count(),
            'unread' => Feedback::where('is_read', false)->count(),
            'read' => Feedback::where('is_read', true)->count(),
        ];

        return view('admin.feedbacks.index', compact('feedbacks', 'status', 'counts'));
    }

    /**
     * Display the specified feedback and optionally mark as read.
     */
    public function show(Feedback $feedback): View
    {
        return view('admin.feedbacks.show', compact('feedback'));
    }

    /**
     * Mark the specified feedback as read.
     */
    public function markRead(Feedback $feedback): RedirectResponse
    {
        $feedback->update(['is_read' => true]);

        return back()->with('success', 'Đã đánh dấu là đã đọc.');
    }

    /**
     * Mark the specified feedback as unread.
     */
    public function markUnread(Feedback $feedback): RedirectResponse
    {
        $feedback->update(['is_read' => false]);

        return back()->with('success', 'Đã đánh dấu là chưa đọc.');
    }

    /**
     * Remove the specified feedback from storage.
     */
    public function destroy(Feedback $feedback): RedirectResponse
    {
        $feedback->delete();

        return redirect()->route('admin.feedbacks.index')->with('success', 'Đã xóa phản hồi thành công.');
    }
}
