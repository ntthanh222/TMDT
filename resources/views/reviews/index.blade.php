@extends('layouts.app')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Back -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <a href="{{ url('/') }}" class="hover:text-amber-600 transition">Trang chủ</a>
                <span>/</span>
                <span class="text-gray-800 font-medium">Đánh giá sản phẩm</span>
            </div>
            <a href="{{ route('contact.create') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium underline">
                Cần hỗ trợ? Liên hệ với chúng tôi
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Product Summary Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex flex-col md:flex-row items-center gap-6">
                <!-- Product Image -->
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center shrink-0 border border-gray-200">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-12 h-12 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-xs">Chưa có ảnh</span>
                        </div>
                    @endif
                </div>

                <!-- Product Info & Stats -->
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-wrap items-center gap-2 justify-center md:justify-start mb-1">
                        @if ($product->category)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                {{ $product->category->name }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-500 font-mono">Mã: {{ $product->sku }}</span>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>

                    <div class="flex items-baseline justify-center md:justify-start gap-3 mb-4">
                        @if ($product->sale_price)
                            <span class="text-2xl font-extrabold text-red-600">{{ number_format($product->sale_price, 0, ',', '.') }} đ</span>
                            <span class="text-sm text-gray-400 line-through">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                        @else
                            <span class="text-2xl font-extrabold text-amber-600">{{ number_format($product->price, 0, ',', '.') }} đ</span>
                        @endif
                    </div>

                    @if ($product->description)
                        <p class="text-sm text-gray-600 line-clamp-2">{{ $product->description }}</p>
                    @endif
                </div>

                <!-- Rating Score Block -->
                <div class="flex flex-col items-center justify-center p-6 bg-amber-50/50 rounded-xl border border-amber-100 min-w-[200px] text-center">
                    <div class="text-4xl font-extrabold text-amber-500">{{ $averageRating }} <span class="text-lg text-gray-500 font-normal">/ 5</span></div>
                    <div class="flex items-center my-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-amber-400 fill-amber-400' : 'text-gray-300 fill-gray-300' }}" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <div class="text-xs text-gray-500">{{ $totalApproved }} đánh giá đã duyệt</div>
                </div>
            </div>

            <!-- Rating Breakdown / Distribution -->
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Phân bố đánh giá</h3>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    @for ($star = 5; $star >= 1; $star--)
                        @php
                            $count = $ratingCounts[$star] ?? 0;
                            $percent = $totalApproved > 0 ? round(($count / $totalApproved) * 100) : 0;
                        @endphp
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="w-12 font-medium text-gray-600 flex items-center">{{ $star }} ★</span>
                            <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-amber-400 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                            <span class="w-12 text-right text-gray-500">{{ $count }} ({{ $percent }}%)</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left/Top Column: Review Form or Login Prompt -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                    @auth
                        @include('reviews.form')
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800 mb-1">Đăng nhập để đánh giá</h3>
                            <p class="text-xs text-gray-500 mb-4">Bạn cần đăng nhập tài khoản để chia sẻ cảm nhận về sản phẩm này.</p>
                            <a href="{{ route('login') }}" class="inline-flex justify-center w-full px-4 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-semibold transition shadow-sm">
                                Đăng nhập ngay
                            </a>
                            <p class="mt-3 text-xs text-gray-500">
                                Chưa có tài khoản? <a href="{{ route('register') }}" class="text-amber-600 font-medium hover:underline">Đăng ký</a>
                            </p>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Right Column: Reviews List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900">
                            Nhận xét từ khách hàng ({{ $totalApproved }})
                        </h2>
                    </div>

                    @include('reviews.list')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection