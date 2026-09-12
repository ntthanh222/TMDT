@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Sản phẩm</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900">Chỉnh sửa sản phẩm</h1>
        </div>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            Quay lại danh sách
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.5fr_0.9fr]">
                <div class="space-y-5">
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    </div>

                    <div>
                        <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700">Mô tả sản phẩm</label>
                        <textarea id="description" name="description" rows="6" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label for="category_id" class="mb-1.5 block text-sm font-medium text-gray-700">Danh mục <span class="text-red-500">*</span></label>
                        <select id="category_id" name="category_id" required class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sku" class="mb-1.5 block text-sm font-medium text-gray-700">Mã SKU</label>
                        <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    </div>

                    <div>
                        <label for="price" class="mb-1.5 block text-sm font-medium text-gray-700">Giá gốc (VNĐ) <span class="text-red-500">*</span></label>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" min="0" required class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    </div>

                    <div>
                        <label for="sale_price" class="mb-1.5 block text-sm font-medium text-gray-700">Giá khuyến mãi (VNĐ)</label>
                        <input type="number" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    </div>

                    <div>
                        <label for="stock_quantity" class="mb-1.5 block text-sm font-medium text-gray-700">Số lượng tồn kho <span class="text-red-500">*</span></label>
                        <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" min="0" required class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    </div>

                    <div>
                        <label for="image" class="mb-1.5 block text-sm font-medium text-gray-700">Ảnh đại diện mới</label>
                        <input type="file" id="image" name="image" accept="image/*" class="w-full rounded-xl border border-dashed border-gray-300 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 shadow-sm file:mr-3 file:rounded-md file:border-0 file:bg-emerald-600 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-white hover:file:bg-emerald-700">
                        @if ($product->image)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-20 w-20 rounded-lg object-cover ring-1 ring-gray-200">
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3 pt-1">
                        <label class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            Cho phép hiển thị / bán
                        </label>

                        <label class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                            Sản phẩm nổi bật
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-5">
                <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Hủy
                </a>
                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    Cập nhật sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
