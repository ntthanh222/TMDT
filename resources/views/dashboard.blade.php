<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trang chủ') }}
        </h2>
    </x-slot>

    @php
        $totalProducts = \App\Models\Product::count();
        $totalOrders = \App\Models\Order::count();
        $totalReviews = \App\Models\Review::where('is_approved', true)->count();
        $recentOrders = \App\Models\Order::with('user')->latest()->limit(5)->get();
        $recentProducts = \App\Models\Product::latest()->limit(4)->get();
    @endphp

    <div class="py-12 bg-gradient-to-br from-amber-50 via-white to-orange-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <section class="mb-8 overflow-hidden rounded-3xl border border-[#e7dfd6] bg-[#f5efe8] p-8 shadow-sm">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#a75d16]">GoodCafe</p>
                        <h1 class="mt-3 text-3xl font-bold text-[#241b17] sm:text-4xl">Nguyên liệu pha chế chất lượng cho mọi quán</h1>
                        <p class="mt-4 text-base leading-7 text-[#453a35]">
                            Chúng tôi mang đến những nguyên liệu tươi ngon, đáng tin cậy và phù hợp cho cà phê, trà, bánh ngọt và các món thức uống sáng tạo.
                            Với tiêu chí an toàn, nhanh chóng và dịch vụ tận tâm, bạn dễ dàng tìm thấy lựa chọn đúng cho doanh nghiệp của mình.
                        </p>
                    </div>

                </div>
            </section>

            <section id="gioi-thieu" class="mb-8 rounded-2xl border border-amber-100 bg-white p-6 shadow-sm">
                <div class="grid gap-6 md:grid-cols-3">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-600">Về chúng tôi</p>
                        <h2 class="mt-2 text-2xl font-bold text-gray-900">Giới thiệu</h2>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-base leading-7 text-gray-600">
                            GoodCafe là nền tảng cung cấp nguyên liệu pha chế cho các quán cafe, nhà hàng và doanh nghiệp thực phẩm.
                            Chúng tôi tập trung vào chất lượng nguyên liệu, giá cả hợp lý và tốc độ giao hàng đáng tin cậy để hỗ trợ hoạt động kinh doanh ngày càng hiệu quả.
                        </p>
                    </div>
                </div>
            </section>

            <div class="mt-8 rounded-2xl border border-gray-200 bg-[#f3f3f3] p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900">Sản phẩm mới</h2>
                    <a href="{{ route('products.search') }}" class="text-base font-medium text-[#c77a2d] hover:text-[#a85a1b]">Xem tất cả</a>
                </div>

                @if ($recentProducts->isEmpty())
                    <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500">
                        Chưa có sản phẩm nào được tạo.
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5 sm:gap-6">
                        @foreach ($recentProducts as $product)
                            <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition duration-200 overflow-hidden flex flex-col border border-gray-100">
                                <div class="aspect-square w-full overflow-hidden bg-gray-100 rounded-t-xl">
                                    @php
                                        $imageUrl = $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/400x400?text=No+Image';
                                    @endphp
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center transition duration-200 hover:scale-[1.02]">
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
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
