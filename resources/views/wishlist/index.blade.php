<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Danh sách yêu thích</h1>
            <a href="{{ route('products.search') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                ← Quay lại mua sắm
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-100 border border-green-200 text-green-700 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($items->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-8 text-center text-gray-500">
                Chưa có sản phẩm yêu thích nào.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($items as $item)
                    @php($product = $item->product)
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="aspect-square bg-gray-100">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/400x400?text=No+Image' }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-gray-400 mb-1">{{ $product->category->name ?? 'Sản phẩm' }}</p>
                            <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ $product->name }}</h2>
                            <div class="mb-3">
                                @if ($product->sale_price)
                                    <span class="text-gray-400 line-through text-sm mr-2">{{ number_format($product->price) }}đ</span>
                                    <span class="text-red-600 font-bold">{{ number_format($product->sale_price) }}đ</span>
                                @else
                                    <span class="text-gray-800 font-bold">{{ number_format($product->price) }}đ</span>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('wishlist.remove', $product) }}" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-3 py-2 rounded-md">
                                        Xóa khỏi yêu thích
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
