<x-app-layout>
<div class="max-w-7xl mx-auto px-4 py-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Nguyên liệu pha chế</h1>

    {{-- Bộ lọc --}}
    <form method="GET" action="{{ route('products.search') }}"
          class="bg-white shadow-sm rounded-lg p-4 mb-8 grid grid-cols-1 md:grid-cols-5 gap-3 items-end">

        <div class="md:col-span-2">
            <label class="block text-sm text-gray-600 mb-1">Tìm kiếm</label>
            <input type="text" name="keyword" value="{{ request('keyword') }}"
                   placeholder="Tìm nguyên liệu..."
                   class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Danh mục</label>
            <select name="category_id"
                    class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="">Tất cả</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-2">
            <div class="w-1/2">
                <label class="block text-sm text-gray-600 mb-1">Giá từ</label>
                <input type="number" name="min_price" value="{{ request('min_price') }}"
                       class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <div class="w-1/2">
                <label class="block text-sm text-gray-600 mb-1">Giá đến</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}"
                       class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
        </div>

        <div class="flex gap-2">
            <div class="flex-1">
                <label class="block text-sm text-gray-600 mb-1">Sắp xếp</label>
                <select name="sort"
                        class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    <option value="best_selling" {{ request('sort') == 'best_selling' ? 'selected' : '' }}>Bán chạy</option>
                </select>
            </div>
            <button type="submit"
                    class="self-end bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                Lọc
            </button>
        </div>
    </form>

    {{-- Danh sách sản phẩm --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition overflow-hidden flex flex-col">
                <div class="aspect-square bg-gray-100">
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/400x400?text=No+Image' }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="p-3 flex flex-col flex-1">
                    <p class="text-xs text-gray-400 mb-1">{{ $product->category->name ?? '' }}</p>
                    <h3 class="text-sm font-medium text-gray-800 line-clamp-2 mb-2 flex-1">
                        {{ $product->name }}
                    </h3>
                    <div>
                        @if($product->sale_price)
                            <span class="text-gray-400 line-through text-xs mr-1">{{ number_format($product->price) }}đ</span>
                            <span class="text-red-600 font-semibold">{{ number_format($product->sale_price) }}đ</span>
                        @else
                            <span class="text-gray-800 font-semibold">{{ number_format($product->price) }}đ</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-16">
                Không tìm thấy sản phẩm nào phù hợp.
            </div>
        @endforelse
    </div>

    {{-- Phân trang --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>
</x-app-layout>
