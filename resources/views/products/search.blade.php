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
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5 sm:gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition duration-200 overflow-hidden flex flex-col border border-gray-100">
                <div class="aspect-square w-full overflow-hidden bg-gray-100 rounded-t-xl">
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/400x400?text=No+Image' }}"
                         class="h-full w-full object-cover object-center transition duration-200 hover:scale-[1.02]">
                </div>
                <div class="p-3 sm:p-3.5 flex flex-col flex-1">
                    <p class="text-[11px] uppercase tracking-wide text-gray-400 mb-1.5">{{ $product->category->name ?? '' }}</p>
                    <h3 class="text-sm font-medium text-gray-800 line-clamp-2 leading-5 mb-2 flex-1 min-h-[2.5rem]">
                        {{ $product->name }}
                    </h3>
                    <div class="space-y-1">
                        @if($product->sale_price)
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-gray-400 line-through text-xs">{{ number_format($product->price) }}đ</span>
                                <span class="text-red-600 font-semibold text-sm">{{ number_format($product->sale_price) }}đ</span>
                            </div>
                        @else
                            <span class="text-gray-800 font-semibold text-sm">{{ number_format($product->price) }}đ</span>
                        @endif
                    </div>

                    <div class="mt-3 flex items-center gap-2">
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-1">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white text-sm font-medium rounded-md px-3 py-2.5 leading-none">
                                Thêm vào giỏ
                            </button>
                        </form>

                        @auth
                            <form method="POST" action="{{ route('wishlist.add', $product) }}">
                                @csrf
                                <button type="submit" class="h-10 w-10 border border-red-200 text-red-500 hover:bg-red-50 rounded-md text-sm font-medium flex items-center justify-center leading-none">
                                    ♥
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500 py-16">
                Không tìm thấy sản phẩm nào phù hợp.
            </div>
        @endforelse
    </div>

    <section id="chinh-sach-mua-hang" class="mt-10">
        <div class="mb-5">
            <h2 class="text-xl font-bold text-gray-800">Chính sách mua hàng</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div id="doi-tra" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-lg text-amber-700">↩️</div>
                    <h3 class="text-base font-semibold text-gray-800">Đổi trả</h3>
                </div>
                <p class="text-sm leading-6 text-gray-600">Đổi mới trong vòng 7 ngày nếu sản phẩm lỗi do nhà sản xuất, sai mẫu mã hoặc không đúng mô tả.</p>
            </div>

            <div id="bao-hanh" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-lg text-emerald-700">🛡️</div>
                    <h3 class="text-base font-semibold text-gray-800">Bảo hành</h3>
                </div>
                <p class="text-sm leading-6 text-gray-600">Tất cả sản phẩm được hỗ trợ bảo hành chính hãng theo quy định, với thời gian rõ ràng trước khi giao hàng.</p>
            </div>

            <div id="van-chuyen" class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center gap-3 mb-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-sky-100 text-lg text-sky-700">🚚</div>
                    <h3 class="text-base font-semibold text-gray-800">Vận chuyển</h3>
                </div>
                <p class="text-sm leading-6 text-gray-600">Giao hàng nhanh trong nội thành, miễn phí vận chuyển cho đơn hàng đạt mức tối thiểu theo khuyến mãi hiện hành.</p>
            </div>
        </div>
    </section>

    {{-- Phân trang --}}
    <div class="mt-8">
        {{ $products->links() }}
    </div>

</div>
</x-app-layout>
