@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Liên Hệ & Góp Ý
            </h1>
            <p class="mt-3 max-w-2xl mx-auto text-base text-gray-500">
                Chúng tôi luôn lắng nghe mọi ý kiến đóng góp và sẵn sàng giải đáp mọi thắc mắc của bạn về sản phẩm và dịch vụ của CoffeeShop.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-8 p-5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="font-bold text-sm">Gửi phản hồi thành công!</h4>
                        <p class="text-sm mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Left Info Panel -->
            <div class="bg-gradient-to-br from-amber-700 to-amber-900 text-white p-8 lg:p-10 flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-4">Thông tin liên hệ</h3>
                    <p class="text-amber-100 text-sm mb-8 leading-relaxed">
                        Bạn có câu hỏi hoặc cần hỗ trợ đặt hàng số lượng lớn? Hãy liên hệ với chúng tôi qua các kênh dưới đây:
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-amber-300 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm text-amber-100">Số 1 Võ Văn Ngân, Phường Linh Chiểu, TP. Thủ Đức, TP. HCM</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-sm text-amber-100">0909 123 456 / 028 3896 0000</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm text-amber-100">contact@cafeshop.com</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-sm text-amber-100">Thứ 2 - Thứ 7: 8:00 - 18:00</span>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-amber-800/60 mt-8 text-xs text-amber-200">
                    Hân hạnh được phục vụ quý khách hàng.
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="lg:col-span-2 p-8 lg:p-10">
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-gray-700 mb-1">
                                Họ và tên <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" 
                                   value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" 
                                   class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('name') border-red-500 @enderror"
                                   placeholder="Nguyễn Văn A" required>
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-semibold text-gray-700 mb-1">
                                Địa chỉ Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" 
                                   value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" 
                                   class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('email') border-red-500 @enderror"
                                   placeholder="name@example.com" required>
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="phone" class="block text-xs font-semibold text-gray-700 mb-1">
                                Số điện thoại
                            </label>
                            <input type="text" id="phone" name="phone" 
                                   value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}" 
                                   class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('phone') border-red-500 @enderror"
                                   placeholder="0912 345 678">
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-semibold text-gray-700 mb-1">
                                Chủ đề liên hệ
                            </label>
                            <input type="text" id="subject" name="subject" 
                                   value="{{ old('subject') }}" 
                                   class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('subject') border-red-500 @enderror"
                                   placeholder="Hỏi về sản phẩm / Khiếu nại / Khác">
                            @error('subject')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="message" class="block text-xs font-semibold text-gray-700 mb-1">
                            Nội dung chi tiết <span class="text-red-500">*</span>
                        </label>
                        <textarea id="message" name="message" rows="5" 
                                  class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('message') border-red-500 @enderror"
                                  placeholder="Nhập nội dung bạn muốn gửi tới chúng tôi..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-sm font-semibold transition shadow-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Gửi liên hệ & góp ý
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection