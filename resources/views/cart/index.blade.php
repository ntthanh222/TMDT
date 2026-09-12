<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Giỏ hàng của bạn</h1>
            <a href="{{ route('products.search') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                ← Tiếp tục mua sắm
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($items->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                Giỏ hàng của bạn đang trống.
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($items as $item)
                        <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-24 h-24 bg-gray-100 rounded-md overflow-hidden">
                                <img src="{{ $item->product->image ? asset('storage/'.$item->product->image) : 'https://placehold.co/200x200?text=No+Image' }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover">
                            </div>

                            <div class="flex-1">
                                <h2 class="text-lg font-semibold text-gray-800">{{ $item->product->name }}</h2>
                                <p class="text-sm text-gray-500">{{ $item->product->category->name ?? 'Sản phẩm' }}</p>
                                <div class="mt-2 text-sm text-gray-700">
                                    @if ($item->product->sale_price)
                                        <span class="line-through text-gray-400 mr-2">{{ number_format($item->product->price) }}đ</span>
                                        <span class="font-bold text-red-600">{{ number_format($item->product->sale_price) }}đ</span>
                                    @else
                                        <span class="font-bold text-gray-800">{{ number_format($item->product->price) }}đ</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('cart.update', $item) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center border rounded-md overflow-hidden">
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99"
                                               class="w-16 px-2 py-2 text-center border-none focus:ring-0">
                                        <button type="submit" class="px-3 py-2 bg-indigo-600 text-white text-sm hover:bg-indigo-700">
                                            Cập nhật
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('cart.remove', $item) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700">
                                        Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <aside class="bg-white rounded-lg shadow-sm p-5 h-fit">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Tóm tắt đơn hàng</h2>

                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Tạm tính</span>
                            <span>{{ number_format($subtotal) }}đ</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phí vận chuyển</span>
                            <span>0đ</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between text-base font-bold text-gray-800">
                            <span>Tổng cộng</span>
                            <span>{{ number_format($subtotal) }}đ</span>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('orders.checkout') }}" class="mt-6">
                        <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-medium py-3 rounded-md">
                            Tiến hành đặt hàng
                        </button>
                    </form>
                </aside>
            </div>
        @endif
    </div>
</x-app-layout>
