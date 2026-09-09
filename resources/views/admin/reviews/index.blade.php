@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quản Lý Đánh Giá Sản Phẩm</h1>
            <p class="text-sm text-gray-500 mt-1">Duyệt, ẩn hoặc xóa các đánh giá sản phẩm từ khách hàng.</p>
        </div>
    </div>

    <!-- Filters & Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-wrap items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.reviews.index', ['status' => 'all', 'rating' => request('rating')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $status === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Tất cả ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending', 'rating' => request('rating')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $status === 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Chờ duyệt ({{ $counts['pending'] }})
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved', 'rating' => request('rating')]) }}"
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $status === 'approved' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                Đã duyệt ({{ $counts['approved'] }})
            </a>
        </div>

        <!-- Rating Filter -->
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex items-center space-x-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <label for="rating" class="text-xs font-medium text-gray-500">Lọc sao:</label>
            <select name="rating" id="rating" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 py-1.5">
                <option value="">Tất cả sao</option>
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                @endfor
            </select>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">ID</th>
                        <th class="px-6 py-3.5">Sản phẩm</th>
                        <th class="px-6 py-3.5">Khách hàng</th>
                        <th class="px-6 py-3.5">Số sao</th>
                        <th class="px-6 py-3.5">Nhận xét</th>
                        <th class="px-6 py-3.5">Trạng thái</th>
                        <th class="px-6 py-3.5">Ngày tạo</th>
                        <th class="px-6 py-3.5 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">#{{ $review->id }}</td>
                            <td class="px-6 py-4">
                                <a href="{{ route('reviews.index', $review->product_id) }}" target="_blank" class="font-medium text-gray-900 hover:text-emerald-600 line-clamp-1">
                                    {{ $review->product->name ?? 'Sản phẩm đã xóa' }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800 text-xs">{{ $review->user->name ?? 'Người dùng' }}</div>
                                <div class="text-xs text-gray-400">{{ $review->user->email ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-50 text-amber-600">
                                    {{ $review->rating }} ★
                                </span>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <p class="text-xs text-gray-600 line-clamp-2">{{ $review->comment ?: '(Không có nhận xét)' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($review->is_approved)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        Đã duyệt
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        Chờ duyệt
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                {{ $review->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                                <a href="{{ route('admin.reviews.show', $review) }}" class="text-gray-600 hover:text-gray-900 font-semibold">
                                    Chi tiết
                                </a>

                                @if (!$review->is_approved)
                                    <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 font-semibold">
                                            Duyệt
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.reviews.hide', $review) }}" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-amber-600 hover:text-amber-800 font-semibold">
                                            Ẩn
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
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
                                Không tìm thấy đánh giá nào phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reviews->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection