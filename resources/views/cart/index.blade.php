<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Giỏ hàng của bạn</h1>
            <a href="{{ route('products.search') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
                ← Tiếp tục mua sắm
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-md bg-rose-100 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($items->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <p class="text-lg font-medium text-gray-700">Giỏ hàng của bạn đang trống.</p>
                <a href="{{ route('products.search') }}" class="mt-4 inline-block bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-medium text-sm hover:bg-emerald-700">
                    Khám phá sản phẩm GoodCafe
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($items as $item)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row gap-4 items-center">
                            <div class="w-full sm:w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                <img src="{{ $item->product->image_url }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1 text-center sm:text-left">
                                <h2 class="text-base font-bold text-gray-900">{{ $item->product->name }}</h2>
                                <p class="text-xs text-gray-500">{{ $item->product->category->name ?? 'Cà phê' }}</p>
                                <div class="mt-2 text-sm text-gray-700">
                                    @if ($item->product->sale_price)
                                        <span class="line-through text-gray-400 mr-2 text-xs">{{ number_format($item->product->price) }}đ</span>
                                        <span class="font-bold text-emerald-600">{{ number_format($item->product->sale_price) }}đ</span>
                                    @else
                                        <span class="font-bold text-gray-900">{{ number_format($item->product->price) }}đ</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99"
                                               class="w-14 px-2 py-1.5 text-center border-none text-sm focus:ring-0">
                                        <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-semibold hover:bg-gray-200 border-l border-gray-300">
                                            Cập nhật
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('cart.remove', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-700 font-medium px-2 py-1">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 h-fit space-y-6">
                    <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Tóm tắt đơn hàng</h2>

                    {{-- Coupon Form --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Mã giảm giá</label>
                        @if ($coupon)
                            <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                                <div>
                                    <span class="font-bold text-emerald-800 text-sm">{{ $coupon['code'] }}</span>
                                    <p class="text-xs text-emerald-600">Giảm {{ number_format($discount) }}đ</p>
                                </div>
                                <form method="POST" action="{{ route('cart.coupon.remove') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-rose-600 hover:text-rose-700 font-semibold">Hủy mã</button>
                                </form>
                            </div>
                        @else
                            <form method="POST" action="{{ route('cart.coupon.apply') }}" class="flex gap-2">
                                @csrf
                                <input type="text" name="coupon_code" placeholder="Nhập mã (VD: GOODCAFE10)" class="flex-1 rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500 uppercase">
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                                    Áp dụng
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Price Breakdown --}}
                    <div class="space-y-3 text-sm text-gray-600 border-t border-gray-100 pt-4">
                        <div class="flex justify-between">
                            <span>Tạm tính</span>
                            <span class="font-semibold text-gray-900">{{ number_format($subtotal) }}đ</span>
                        </div>

                        @if ($discount > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Giảm giá</span>
                                <span class="font-semibold">-{{ number_format($discount) }}đ</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span>Phí vận chuyển</span>
                            <span class="text-emerald-600 font-semibold">Miễn phí</span>
                        </div>

                        <div class="border-t border-gray-200 pt-3 flex justify-between text-base font-bold text-gray-900">
                            <span>Tổng cộng</span>
                            <span class="text-emerald-600 text-xl">{{ number_format($total) }}đ</span>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('orders.checkout') }}">
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition shadow-sm">
                            Tiến hành đặt hàng →
                        </button>
                    </form>
                </aside>
            </div>
        @endif
    </div>
</x-app-layout>
