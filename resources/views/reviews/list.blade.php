@if ($approvedReviews->isEmpty())
    <div class="text-center py-12">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <h4 class="text-base font-medium text-gray-700 mb-1">Chưa có đánh giá nào</h4>
        <p class="text-sm text-gray-500">Hãy là người đầu tiên trải nghiệm và chia sẻ cảm nhận về sản phẩm này!</p>
    </div>
@else
    <div class="divide-y divide-gray-100">
        @foreach ($approvedReviews as $review)
            @include('reviews.item', ['review' => $review])
        @endforeach
    </div>

    <div class="mt-6">
        {{ $approvedReviews->links() }}
    </div>
@endif