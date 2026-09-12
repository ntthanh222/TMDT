<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>GoodCafe</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-800">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1">
                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            <footer class="mt-10 border-t border-gray-200 bg-[#ededed] text-gray-700">
                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <div class="grid gap-8 md:grid-cols-3">
                        <div>
                            <h3 class="mb-3 text-[15px] font-semibold uppercase tracking-wide text-[#2c2c2c]">GoodCafe</h3>
                            <p class="text-sm leading-6 text-gray-600">
                                Cung cấp nguyên liệu pha chế chất lượng, an toàn và phù hợp cho quán cafe, nhà hàng và kinh doanh thực phẩm.
                            </p>
                        </div>

                        <div>
                            <h4 class="mb-3 text-[13px] font-semibold uppercase tracking-wider text-[#2c2c2c]">Hỗ trợ</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li><a href="{{ route('contact.create') }}" class="hover:text-gray-900">Liên hệ</a></li>
                                <li><a href="{{ route('policies.return') }}" class="hover:text-gray-900">Đổi trả</a></li>
                                <li><a href="{{ route('policies.warranty') }}" class="hover:text-gray-900">Bảo hành</a></li>
                                <li><a href="{{ route('policies.shipping') }}" class="hover:text-gray-900">Vận chuyển</a></li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="mb-3 text-[13px] font-semibold uppercase tracking-wider text-[#2c2c2c]">Liên hệ</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li>📍 70 Tô Ký, Trung Mỹ Tây</li>
                                <li>📞 0909 123 456</li>
                                <li>✉️ support@goodcafe.vn</li>
                                <li>🕒 8:00 - 20:00</li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-300 pt-5 text-center text-sm text-gray-500">
                        © {{ date('Y') }} GoodCafe. Mọi quyền được bảo lưu.
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>