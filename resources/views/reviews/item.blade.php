<div class="py-5">
    <div class="flex items-start justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-sm uppercase">
                {{ substr($review->user->name ?? 'K', 0, 1) }}
            </div>
            <div>
                <div class="font-semibold text-gray-900 text-sm">
                    {{ $review->user->name ?? 'Khách hàng' }}
                </div>
                <div class="text-xs text-gray-400">
                    {{ $review->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        <div class="flex items-center">
            @for ($i = 1; $i <= 5; $i++)
                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-200 fill-gray-200' }}" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endfor
        </div>
    </div>

    @if ($review->comment)
        <p class="mt-3 text-sm text-gray-700 leading-relaxed pl-13">
            {{ $review->comment }}
        </p>
    @endif
</div>