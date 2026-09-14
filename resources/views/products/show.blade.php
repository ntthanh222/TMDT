<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Breadcrumb --}}
        <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-2">
                <li><a href="{{ route('home') }}" class="hover:text-emerald-600">Trang chủ</a></li>
                <li><span>/</span></li>
                <li><a href="{{ route('products.search') }}" class="hover:text-emerald-600">Sản phẩm</a></li>
                <li><span>/</span></li>
                <li class="font-medium text-gray-800 truncate max-w-xs">{{ $product->name }}</li>
            </ol>
        </nav>

        {{-- Session Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Main Product Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8 grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            {{-- Product Image --}}
            <div class="space-y-4">
                <div class="aspect-square rounded-xl overflow-hidden bg-gray-50 border border-gray-100 flex items-center justify-center">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
            </div>

            {{-- Product Info & Purchase Form --}}
            <div class="flex flex-col justify-between">
                <div>
                    <span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full uppercase tracking-wider mb-3">
                        {{ $product->category->name ?? 'Sản phẩm' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>

                    {{-- Ratings --}}
                    <div class="flex items-center gap-2 mb-4 text-sm">
                        <div class="flex text-amber-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($avgRating) ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="font-semibold text-gray-700">{{ $avgRating }}</span>
                        <span class="text-gray-400">({{ $approvedReviews->count() }} đánh giá)</span>
                        <span class="text-gray-300">|</span>
                        <span class="text-gray-500">Mã SP: <span class="font-mono text-gray-700">{{ $product->sku }}</span></span>
                    </div>

                    {{-- Pricing --}}
                    <div class="flex items-baseline gap-3 mb-6">
                        @if ($product->sale_price)
                            <span class="text-3xl font-extrabold text-emerald-600">{{ number_format($product->sale_price) }}đ</span>
                            <span class="text-lg text-gray-400 line-through">{{ number_format($product->price) }}đ</span>
                            <span class="px-2 py-0.5 text-xs font-bold bg-rose-100 text-rose-700 rounded-md">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                        @else
                            <span class="text-3xl font-extrabold text-gray-900">{{ number_format($product->price) }}đ</span>
                        @endif
                    </div>

                    {{-- Stock Status --}}
                    <div class="mb-6 text-sm">
                        <span class="text-gray-600">Tình trạng:</span>
                        @if ($product->stock_quantity > 0)
                            <span class="ml-2 font-semibold text-emerald-600">Còn hàng ({{ $product->stock_quantity }} sản phẩm)</span>
                        @else
                            <span class="ml-2 font-semibold text-rose-600">Hết hàng</span>
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="border-t border-b border-gray-100 py-4 mb-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Mô tả sản phẩm</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="space-y-4">
                    @auth
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex flex-wrap items-center gap-4">
                            @csrf
                            <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden bg-gray-50">
                                <label for="quantity" class="sr-only">Số lượng</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                                       class="w-16 text-center border-0 bg-transparent py-2.5 text-sm font-semibold text-gray-900 focus:ring-0">
                            </div>
                            <button type="submit" {{ $product->stock_quantity < 1 ? 'disabled' : '' }}
                                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 px-6 rounded-xl transition duration-200 flex items-center justify-center gap-2 disabled:bg-gray-300 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>Thêm vào giỏ hàng</span>
                            </button>
                        </form>

                        <form action="{{ route('wishlist.add', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full border border-gray-200 hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-rose-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                                <span>Thêm vào danh sách yêu thích</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white text-center font-medium py-3 px-6 rounded-xl transition duration-200">
                            Đăng nhập để đặt mua sản phẩm
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Product Reviews Section --}}
        <div class="mt-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Đánh giá sản phẩm</h2>
                <a href="{{ route('reviews.index', $product) }}" class="text-sm font-medium text-emerald-600 hover:underline">Xem tất cả đánh giá &rarr;</a>
            </div>

            @if ($approvedReviews->isEmpty())
                <p class="text-sm text-gray-500 italic">Chưa có đánh giá nào được phê duyệt cho sản phẩm này.</p>
            @else
                <div class="space-y-4">
                    @foreach ($approvedReviews->take(3) as $review)
                        <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold text-sm text-gray-800">{{ $review->user->name ?? 'Khách hàng' }}</span>
                                <div class="flex text-amber-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200 fill-current' }}" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mb-1">{{ $review->comment }}</p>
                            <span class="text-[11px] text-gray-400">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Related Products --}}
        @if ($relatedProducts->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Sản phẩm liên quan</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach ($relatedProducts as $rel)
                        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition duration-200 overflow-hidden flex flex-col border border-gray-100">
                            <a href="{{ route('products.show', $rel) }}" class="aspect-square w-full overflow-hidden bg-gray-100 block">
                                <img src="{{ $rel->image_url }}" alt="{{ $rel->name }}" class="h-full w-full object-cover hover:scale-[1.02] transition duration-200">
                            </a>
                            <div class="p-3.5 flex flex-col flex-1">
                                <p class="text-[11px] uppercase tracking-wide text-gray-400 mb-1">{{ $rel->category->name ?? '' }}</p>
                                <a href="{{ route('products.show', $rel) }}" class="text-sm font-medium text-gray-800 line-clamp-2 hover:text-emerald-600 flex-1 min-h-[2.5rem]">
                                    {{ $rel->name }}
                                </a>
                                <p class="text-sm font-bold text-emerald-600 mt-2">{{ number_format($rel->sale_price ?? $rel->price) }}đ</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
