@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Top Nav -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.feedbacks.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Quay lại danh sách liên hệ & góp ý
        </a>

        <div class="flex items-center space-x-3">
            @if (!$feedback->is_read)
                <form method="POST" action="{{ route('admin.feedbacks.read', $feedback) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm">
                        Đánh dấu đã đọc
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('admin.feedbacks.unread', $feedback) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-sm">
                        Đánh dấu chưa đọc
                    </button>
                </form>
            @endif

            <form method="POST" action="{{ route('admin.feedbacks.destroy', $feedback) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thư liên hệ này?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg shadow-sm">
                    Xóa
                </button>
            </form>
        </div>
    </div>

    <!-- Feedback Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Chi tiết phản hồi #{{ $feedback->id }}</h2>
            @if ($feedback->is_read)
                <span class="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                    Đã đọc
                </span>
            @else
                <span class="px-3 py-1 bg-amber-100 text-amber-800 text-xs font-bold rounded-full">
                    Chưa đọc
                </span>
            @endif
        </div>

        <div class="p-6 space-y-6">
            <!-- Sender Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Người gửi</h3>
                    <div class="p-4 bg-gray-50 rounded-lg space-y-1">
                        <div class="font-bold text-gray-900 text-sm">{{ $feedback->name }}</div>
                        <div class="text-xs text-gray-600">
                            Email: <a href="mailto:{{ $feedback->email }}" class="text-emerald-600 hover:underline">{{ $feedback->email }}</a>
                        </div>
                        <div class="text-xs text-gray-600">
                            Số điện thoại: {{ $feedback->phone ?: '(Không cung cấp)' }}
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Thời gian & Trạng thái</h3>
                    <div class="p-4 bg-gray-50 rounded-lg space-y-1">
                        <div class="text-xs text-gray-600">Gửi lúc: <span class="font-medium text-gray-900">{{ $feedback->created_at->format('d/m/Y H:i:s') }}</span></div>
                        <div class="text-xs text-gray-600">Cập nhật: <span class="font-medium text-gray-900">{{ $feedback->updated_at->format('d/m/Y H:i:s') }}</span></div>
                    </div>
                </div>
            </div>

            <!-- Subject -->
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Chủ đề</h3>
                <div class="p-4 bg-gray-50 rounded-lg text-sm font-semibold text-gray-800">
                    {{ $feedback->subject ?: '(Không có chủ đề)' }}
                </div>
            </div>

            <!-- Message Content -->
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Nội dung tin nhắn</h3>
                <div class="p-5 bg-gray-50 rounded-lg text-sm text-gray-800 leading-relaxed whitespace-pre-line min-h-[140px]">
                    {{ $feedback->message }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection