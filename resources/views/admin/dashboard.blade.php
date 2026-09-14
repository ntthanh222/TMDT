@extends('admin.layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Dashboard</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900">Bảng điều khiển quản trị</h1>
        </div>
        <a href="{{ route('products.search') }}" target="_blank" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
            Xem cửa hàng
        </a>
    </div>

    {{-- Metrics Grid --}}
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
        {{-- Total Customers --}}
        <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tổng khách hàng</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalCustomers) }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tổng đơn hàng</p>
                    <h3 class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalOrders) }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Đơn chờ xử lý</p>
                    <h3 class="mt-2 text-3xl font-bold text-amber-600">{{ number_format($pendingOrders) }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Revenue --}}
        <div class="rounded-2xl border border-purple-100 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Doanh thu</p>
                    <h3 class="mt-2 text-2xl font-bold text-purple-700">{{ number_format($totalRevenue, 0, ',', '.') }} đ</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100 text-purple-700">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Grid: Best Selling & Recent Orders --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        {{-- Best Selling Products --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Sản phẩm bán chạy</h2>
                <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Xem tất cả</a>
            </div>

            @if ($bestSellingProducts->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500">
                    Chưa có thống kê bán hàng.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($bestSellingProducts as $product)
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-10 w-10 rounded-lg object-cover border">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($product->price, 0, ',', '.') }} đ</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                Đã bán: {{ number_format($product->total_sold ?? 0) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Orders --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Đơn hàng gần đây</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Quản lý đơn</a>
            </div>

            @if ($recentOrders->isEmpty())
                <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500">
                    Chưa có đơn hàng nào.
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($recentOrders as $order)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="font-semibold text-gray-900">Đơn #{{ $order->order_number }}</p>
                                <p class="text-xs text-gray-500">{{ $order->user->name ?? 'Khách hàng' }} • {{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">{{ number_format($order->total, 0, ',', '.') }} đ</p>
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold
                                    @if($order->status === 'completed') bg-emerald-100 text-emerald-800
                                    @elseif($order->status === 'cancelled') bg-rose-100 text-rose-800
                                    @elseif($order->status === 'shipping') bg-blue-100 text-blue-800
                                    @else bg-amber-100 text-amber-800 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
