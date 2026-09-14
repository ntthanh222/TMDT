@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Quản lý danh mục</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900">Danh sách danh mục sản phẩm</h1>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            + Thêm danh mục
        </a>
    </div>

    {{-- Table --}}
    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
            <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Tên danh mục</th>
                    <th class="px-6 py-3">Slug</th>
                    <th class="px-6 py-3">Mô tả</th>
                    <th class="px-6 py-3">Số sản phẩm</th>
                    <th class="px-6 py-3">Trạng thái</th>
                    <th class="px-6 py-3 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">#{{ $category->id }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $category->name }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $category->slug }}</td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate">{{ $category->description ?: 'Không có' }}</td>
                        <td class="px-6 py-4 font-semibold text-gray-700">{{ $category->products_count }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $category->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-900">Sửa</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-rose-600 hover:text-rose-900">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">Chưa có danh mục nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="p-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
