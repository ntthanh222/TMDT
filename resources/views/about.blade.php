<x-app-layout>
    <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Hero Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-block px-3.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full uppercase tracking-wider mb-3">Về Chúng Tôi</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-4">GoodCafe — Thương Hiệu Nguyên Liệu Pha Chế Hàng Đầu</h1>
            <p class="text-base text-gray-600 leading-relaxed">
                Chúng tôi cung cấp nguyên liệu pha chế chất lượng cao, từ hạt cà phê Việt Nam rang mộc nguyên chất đến trà Shan Tuyết, syrup cao cấp và dụng cụ chuyên nghiệp cho các quán cafe, nhà hàng trên toàn quốc.
            </p>
        </div>

        {{-- Features Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Chất Lượng Thật 100%</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Tuyển chọn nghiêm ngặt từ nông trại Đà Lạt, Buôn Ma Thuột và các thương hiệu uy tín hàng đầu thế giới.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Giao Hàng Siêu Tốc</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Đảm bảo chuỗi cung ứng liên tục cho cửa hàng của bạn với dịch vụ vận chuyển nhanh chóng, an toàn.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm text-center">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Hỗ Trợ 24/7</h3>
                <p class="text-sm text-gray-600 leading-relaxed">Đội ngũ chuyên viên sẵn sàng tư vấn công thức pha chế, tối ưu chi phí nguyên liệu cho quán cafe.</p>
            </div>
        </div>

        {{-- Contact Info Box --}}
        <div class="bg-emerald-900 text-white rounded-2xl p-8 lg:p-12 text-center">
            <h2 class="text-2xl font-bold mb-3">Kết Nối Với GoodCafe</h2>
            <p class="text-emerald-200 text-sm max-w-xl mx-auto mb-6">Liên hệ với chúng tôi để nhận báo giá sỉ nguyên liệu pha chế cho quán cafe hoặc đăng ký trải nghiệm sản phẩm mẫu.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('contact.create') }}" class="bg-white text-emerald-900 hover:bg-emerald-50 font-bold py-3 px-6 rounded-xl text-sm transition duration-200">Liên Hệ Ngay</a>
                <a href="{{ route('products.search') }}" class="bg-emerald-800 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition duration-200">Xem Sản Phẩm</a>
            </div>
        </div>
    </div>
</x-app-layout>
