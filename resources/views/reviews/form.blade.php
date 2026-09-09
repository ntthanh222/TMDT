@if ($userReview)
    <div class="mb-4 pb-4 border-b border-gray-100">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold uppercase tracking-wider text-amber-600 bg-amber-50 px-2 py-0.5 rounded">
                Đánh giá của bạn
            </span>
            @if ($userReview->is_approved)
                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Đã duyệt</span>
            @else
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded">Chờ duyệt</span>
            @endif
        </div>
        <p class="text-xs text-gray-500">Bạn đã đánh giá sản phẩm này. Bạn có thể cập nhật nhận xét hoặc số sao bên dưới.</p>
    </div>

    <form action="{{ route('reviews.update', $userReview) }}" method="POST" x-data="{ 
        rating: {{ old('rating', $userReview->rating) }}, 
        hoverRating: 0,
        descriptions: {
            1: '1 sao - Rất tệ',
            2: '2 sao - Tệ',
            3: '3 sao - Bình thường',
            4: '4 sao - Tốt',
            5: '5 sao - Rất tốt'
        }
    }">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label class="block text-xs font-semibold text-gray-700 mb-1">Đánh giá số sao <span class="text-red-500">*</span></label>
            <input type="hidden" name="rating" :value="rating">
            <div class="flex items-center space-x-1 cursor-pointer" @mouseleave="hoverRating = 0">
                <template x-for="star in [1, 2, 3, 4, 5]">
                    <button type="button" 
                            @click="rating = star" 
                            @mouseenter="hoverRating = star"
                            class="text-2xl focus:outline-none transition-transform hover:scale-110">
                        <span x-show="(hoverRating || rating) >= star" class="text-amber-400">★</span>
                        <span x-show="(hoverRating || rating) < star" class="text-gray-300">☆</span>
                    </button>
                </template>
            </div>
            <div class="h-4 mt-1">
                <span class="text-xs font-medium text-amber-600" x-text="descriptions[hoverRating || rating] || 'Chọn số sao'"></span>
            </div>
            @error('rating')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="comment" class="block text-xs font-semibold text-gray-700 mb-1">Nội dung nhận xét</label>
            <textarea id="comment" name="comment" rows="4" 
                      class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" 
                      placeholder="Chia sẻ trải nghiệm sử dụng thực tế của bạn...">{{ old('comment', $userReview->comment) }}</textarea>
            @error('comment')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center space-x-2">
            <button type="submit" class="flex-1 bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                Cập nhật đánh giá
            </button>
        </div>
    </form>

    <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" class="mt-2" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá của mình không?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="w-full text-center text-xs text-red-600 hover:text-red-700 py-1 hover:underline">
            Xóa đánh giá này
        </button>
    </form>
@else
    <h3 class="text-base font-bold text-gray-900 mb-2">Gửi đánh giá của bạn</h3>
    <p class="text-xs text-gray-500 mb-4">Chia sẻ trải nghiệm của bạn để giúp khách hàng khác có quyết định mua hàng tốt hơn.</p>

    <form action="{{ route('reviews.store', $product) }}" method="POST" x-data="{ 
        rating: {{ old('rating', 5) }}, 
        hoverRating: 0,
        descriptions: {
            1: '1 sao - Rất tệ',
            2: '2 sao - Tệ',
            3: '3 sao - Bình thường',
            4: '4 sao - Tốt',
            5: '5 sao - Rất tốt'
        }
    }">
        @csrf

        <div class="mb-4">
            <label class="block text-xs font-semibold text-gray-700 mb-1">Đánh giá số sao <span class="text-red-500">*</span></label>
            <input type="hidden" name="rating" :value="rating">
            <div class="flex items-center space-x-1 cursor-pointer" @mouseleave="hoverRating = 0">
                <template x-for="star in [1, 2, 3, 4, 5]">
                    <button type="button" 
                            @click="rating = star" 
                            @mouseenter="hoverRating = star"
                            class="text-2xl focus:outline-none transition-transform hover:scale-110">
                        <span x-show="(hoverRating || rating) >= star" class="text-amber-400">★</span>
                        <span x-show="(hoverRating || rating) < star" class="text-gray-300">☆</span>
                    </button>
                </template>
            </div>
            <div class="h-4 mt-1">
                <span class="text-xs font-medium text-amber-600" x-text="descriptions[hoverRating || rating] || 'Chọn số sao'"></span>
            </div>
            @error('rating')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="comment" class="block text-xs font-semibold text-gray-700 mb-1">Nội dung nhận xét</label>
            <textarea id="comment" name="comment" rows="4" 
                      class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500" 
                      placeholder="Sản phẩm dùng thế nào? Hương vị, đóng gói có tốt không?">{{ old('comment') }}</textarea>
            @error('comment')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition shadow-sm">
            Gửi đánh giá
        </button>
    </form>
@endif