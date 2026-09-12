<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-3xl">
                    <h2 class="text-lg font-medium text-gray-900">Lịch sử đơn hàng</h2>
                    <p class="mt-1 text-sm text-gray-600">Các đơn hàng gần đây của bạn.</p>

                    @php
                        $recentOrders = Auth::user()->orders()->with(['orderDetails.product'])->latest()->take(5)->get();
                    @endphp

                    @if ($recentOrders->isEmpty())
                        <p class="mt-4 text-sm text-gray-500">Bạn chưa có đơn hàng nào.</p>
                    @else
                        <div class="mt-4 space-y-4">
                            @foreach ($recentOrders as $order)
                                <div class="rounded-lg border border-gray-200 p-4">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500">Mã đơn</p>
                                            <p class="font-semibold text-gray-800">{{ $order->order_code }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Tổng tiền</p>
                                            <p class="font-semibold text-gray-800">{{ number_format($order->total) }}đ</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Trạng thái</p>
                                            <span class="inline-flex rounded-full bg-amber-100 px-2 py-1 text-xs font-semibold text-amber-800">
                                                {{ $order->status === 'pending' ? 'Chờ xác nhận' : ($order->status === 'confirmed' ? 'Đã xác nhận' : ucfirst($order->status)) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-3 text-sm text-gray-600">
                                        @foreach ($order->orderDetails as $detail)
                                            <div>{{ $detail->product_name }} x {{ $detail->quantity }}</div>
                                        @endforeach
                                    </div>

                                    <div class="mt-3">
                                        <a href="{{ route('orders.show', $order) }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
