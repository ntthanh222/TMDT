<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Models\Feedback;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FeedbackController extends Controller
{
    /**
     * Show the form for creating a new feedback/contact message.
     */
    public function create(): View
    {
        return view('feedback.contact');
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
}
