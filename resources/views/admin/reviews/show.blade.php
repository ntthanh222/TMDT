@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Nav -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.reviews.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại danh sách đánh giá
        </a>

        <div class="flex items-center space-x-3">
            @if (!$review->is_approved)
                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm">
                        Duyệt đánh giá
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.reviews.hide', $review) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-sm">
                        Ẩn khỏi công khai
                    </button>
                </form>
            @endif

            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn đánh giá này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg shadow-sm">
                    Xóa
                </button>
            </form>
        </div>
    </div>

    <!-- Review Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Chi tiết đánh giá #{{ $review->id }}</h2>
            @if ($review->is_approved)
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                    Đã duyệt
                </span>
            @else
                <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                    Chờ duyệt
                </span>
            @endif
        </div>

        <div class="p-6 space-y-6">
            <!-- Product Info -->
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Sản phẩm được đánh giá</h3>
                <div class="p-4 bg-gray-50 rounded-lg flex items-center justify-between">
                    <div>
                        <div class="font-bold text-gray-900 text-base">{{ $review->product->name ?? 'Sản phẩm không tồn tại' }}</div>
                        <div class="text-xs text-gray-500">Mã SKU: {{ $review->product->sku ?? 'N/A' }}</div>
                    </div>
                    <a href="{{ route('reviews.index', $review->product_id) }}" target="_blank" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold underline">
                        Xem trang review
                    </a>
                </div>
            </div>

            <!-- Customer & Rating -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Khách hàng</h3>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="font-bold text-gray-900 text-sm">{{ $review->user->name ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-600">{{ $review->user->email ?? 'N/A' }}</div>
                        @if (isset($review->user->phone))
                            <div class="text-xs text-gray-600 mt-1">SĐT: {{ $review->user->phone }}</div>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Mức đánh giá & Thời gian</h3>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-2 mb-1">
                            <span class="text-xl font-bold text-amber-500">{{ $review->rating }} / 5</span>
                            <div class="flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-300' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        <div class="text-xs text-gray-500">Gửi lúc: {{ $review->created_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                </div>
            </div>

            <!-- Comment Content -->
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nội dung nhận xét</h3>
                <div class="p-5 bg-gray-50 rounded-lg text-sm text-gray-800 leading-relaxed min-h-[100px]">
                    {{ $review->comment ?: '(Khách hàng không để lại nhận xét văn bản)' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection