@extends('admin.layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Dashboard</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900">Bảng điều khiển quản trị</h1>
        </div>
        <a href="{{ route('products.search') }}" target="_blank" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            Xem cửa hàng
        </a>
    </div>

    @php
        $totalProducts = \App\Models\Product::count();
        $totalReviews = \App\Models\Review::count();
        $pendingReviews = \App\Models\Review::where('is_approved', false)->count();
        $totalFeedback = \App\Models\Feedback::count();
        $unreadFeedback = \App\Models\Feedback::where('is_read', false)->count();
        $recentReviews = \App\Models\Review::with(['user', 'product'])->latest()->limit(5)->get();
        $recentFeedback = \App\Models\Feedback::latest()->limit(5)->get();
    @endphp

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Sản phẩm</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ $totalProducts }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 7h14l-1 11H6L5 7zm3-2a4 4 0 118 0" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Tổng đánh giá</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ $totalReviews }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Chờ duyệt</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingReviews }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-100 text-sky-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Liên hệ mới</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ $unreadFeedback }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Đánh giá mới nhất</h2>
                <a href="{{ route('admin.reviews.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Quản lý</a>
            </div>

            @if ($recentReviews->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500">
                    Chưa có đánh giá nào.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($recentReviews as $review)
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $review->user->name ?? 'Khách hàng' }}</p>
                                    <p class="text-xs text-gray-500">{{ $review->product->name ?? 'Sản phẩm' }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-700">
                                    {{ $review->rating }}★
                                </span>
                            </div>
                            <p class="mt-2 text-sm text-gray-600">{{ Str::limit($review->comment ?: 'Không có nhận xét', 110) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Phản hồi mới nhất</h2>
                <a href="{{ route('admin.feedbacks.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Quản lý</a>
            </div>

            @if ($recentFeedback->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500">
                    Chưa có phản hồi nào.
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($recentFeedback as $feedback)
                        <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-gray-900">{{ $feedback->name }}</p>
                                @if (!$feedback->is_read)
                                    <span class="inline-flex rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-700">Mới</span>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ $feedback->email }}</p>
                            <p class="mt-2 text-sm text-gray-600">{{ Str::limit($feedback->message, 110) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
