@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quản Lý Phản Hồi & Liên Hệ</h1>
            <p class="text-sm text-gray-500 mt-1">Danh sách ý kiến đóng góp, phản hồi và tin nhắn liên hệ từ khách hàng.</p>
        </div>
    </div>

    <!-- Filters & Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.feedbacks.index', ['status' => 'all']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $status === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Tất cả ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.feedbacks.index', ['status' => 'unread']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $status === 'unread' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Chưa đọc ({{ $counts['unread'] }})
            </a>
            <a href="{{ route('admin.feedbacks.index', ['status' => 'read']) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $status === 'read' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Đã đọc ({{ $counts['read'] }})
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">ID</th>
                        <th class="px-6 py-3.5">Họ tên & Email</th>
                        <th class="px-6 py-3.5">Số điện thoại</th>
                        <th class="px-6 py-3.5">Chủ đề</th>
                        <th class="px-6 py-3.5">Nội dung</th>
                        <th class="px-6 py-3.5">Trạng thái</th>
                        <th class="px-6 py-3.5">Thời gian gửi</th>
                        <th class="px-6 py-3.5 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($feedbacks as $feedback)
                        <tr class="hover:bg-gray-50/80 transition {{ !$feedback->is_read ? 'bg-amber-50/20 font-medium' : '' }}">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">#{{ $feedback->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900 text-xs">{{ $feedback->name }}</div>
                                <div class="text-xs text-gray-500">{{ $feedback->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 whitespace-nowrap">
                                {{ $feedback->phone ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-800 font-medium max-w-xs">
                                <div class="truncate">{{ $feedback->subject ?: '(Không có chủ đề)' }}</div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $feedback->message }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($feedback->is_read)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Đã đọc
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Chưa đọc
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                {{ $feedback->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                <a href="{{ route('admin.feedbacks.show', $feedback) }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                                    Chi tiết
                                </a>

                                @if (!$feedback->is_read)
                                    <form method="POST" action="{{ route('admin.feedbacks.read', $feedback) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 font-semibold">
                                            Đã đọc
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.feedbacks.unread', $feedback) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-amber-600 hover:text-amber-800 font-semibold">
                                            Chưa đọc
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.feedbacks.destroy', $feedback) }}" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500 text-sm">
                                Không tìm thấy liên hệ / phản hồi nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($feedbacks->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $feedbacks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection