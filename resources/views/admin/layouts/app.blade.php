<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>GoodCafe - Quản Trị Hệ Thống</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 text-gray-900">
        <div class="min-h-screen flex flex-col">
            <!-- Admin Top Navigation -->
            <nav class="bg-gray-900 text-white border-b border-gray-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center space-x-6">
                            <a href="{{ route('admin.dashboard') }}" class="font-bold text-lg text-emerald-400 flex items-center">
                                <x-application-logo class="w-6 h-6 mr-2 text-emerald-400" />
                                GoodCafe Admin
                            </a>

                            <div class="hidden md:flex space-x-2">
                                <a href="{{ route('admin.products.index') }}" 
                                   class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 text-white border-b-2 border-emerald-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                    Quản lý sản phẩm
                                </a>
                                <a href="{{ route('admin.reviews.index') }}" 
                                   class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.reviews.*') ? 'bg-gray-800 text-white border-b-2 border-emerald-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                    Quản lý đánh giá
                                </a>
                                <a href="{{ route('admin.orders.index') }}" 
                                   class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.orders.*') ? 'bg-gray-800 text-white border-b-2 border-emerald-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                    Quản lý đơn hàng
                                </a>
                                <a href="{{ route('admin.feedbacks.index') }}" 
                                   class="px-3 py-2 rounded-md text-sm font-medium transition {{ request()->routeIs('admin.feedbacks.*') ? 'bg-gray-800 text-white border-b-2 border-emerald-400' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                                    Quản lý liên hệ & góp ý
                                </a>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <a href="{{ route('products.search') }}" target="_blank" class="text-xs bg-gray-800 hover:bg-gray-700 px-3 py-1.5 rounded text-gray-300">
                                Xem cửa hàng
                            </a>
                            <span class="text-sm text-gray-300">{{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-xs text-red-400 hover:text-red-300 underline">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Alerts -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
                @if (session('success'))
                    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline font-medium">{{ session('error') }}</span>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full">
                @yield('content')
            </main>
        </div>
    </body>
</html>