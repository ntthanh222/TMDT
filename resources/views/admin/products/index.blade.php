@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Sản phẩm</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900">Quản lý sản phẩm</h1>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            + Thêm sản phẩm mới
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Hình ảnh</th>
                        <th class="px-4 py-3">Tên sản phẩm</th>
                        <th class="px-4 py-3">Danh mục</th>
                        <th class="px-4 py-3">Giá bán</th>
                        <th class="px-4 py-3">Tồn kho</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3 font-medium text-gray-600">#{{ $product->id }}</td>
                            <td class="px-4 py-3">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 rounded-lg object-cover ring-1 ring-gray-200">
                                @else
                                    <span class="text-xs text-gray-400">Không có ảnh</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900">{{ $product->name }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $product->category ? $product->category->name : 'N/A' }}</td>
                            <td class="px-4 py-3">
                                @if($product->sale_price)
                                    <div class="font-semibold text-red-600">{{ number_format($product->sale_price) }}đ</div>
                                    <div class="text-xs text-gray-400 line-through">{{ number_format($product->price) }}đ</div>
                                @else
                                    <div class="font-semibold text-gray-700">{{ number_format($product->price) }}đ</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $product->stock_quantity }}</td>
                            <td class="px-4 py-3">
                                @if($product->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">Đang bán</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-200 px-2.5 py-1 text-xs font-semibold text-gray-700">Ẩn</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-200">Sửa</a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-100 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-200">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">Chưa có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection