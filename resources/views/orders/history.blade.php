<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Lịch sử đơn hàng</h1>
                <p class="mt-1 text-sm text-gray-500">Theo dõi trạng thái và chi tiết các đơn hàng bạn đã đặt.</p>
            </div>
            <a href="{{ route('products.search') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">← Tiếp tục mua sắm</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="rounded-xl bg-white p-8 text-center text-gray-500 shadow-sm ring-1 ring-gray-200">
                Bạn chưa có đơn hàng nào.
            </div>
        @else
            <div class="space-y-5">
                @foreach ($orders as $order)
                    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-200">
                        <div class="flex flex-col gap-3 border-b border-gray-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Mã đơn</p>
                                <p class="text-lg font-bold text-gray-800">{{ $order->order_code }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Ngày đặt</p>
                                <p class="font-medium text-gray-700">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tổng tiền</p>
                                <p class="font-bold text-amber-700">{{ number_format($order->total) }}đ</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Trạng thái</p>
                                @php
                                    $statusStyles = [
                                        'pending' => 'bg-amber-100 text-amber-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'shipping' => 'bg-indigo-100 text-indigo-800',
                                        'completed' => 'bg-emerald-100 text-emerald-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                    $labels = [
                                        'pending' => 'Chờ xác nhận',
                                        'confirmed' => 'Đã xác nhận',
                                        'shipping' => 'Đang giao',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy',
                                    ];
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusStyles[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $labels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                            <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-700">
                                Xem chi tiết
                            </a>

                            @if ($order->status === 'pending')
                                <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100">
                                        Hủy đơn hàng
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="mt-4 space-y-3">
                            @foreach ($order->orderDetails as $detail)
                                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-100 bg-gray-50 p-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $detail->product && $detail->product->image ? asset('storage/' . $detail->product->image) : 'https://placehold.co/80x80?text=No+Image' }}" alt="{{ $detail->product_name }}" class="h-14 w-14 rounded-md object-cover">
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $detail->product_name }}</p>
                                            <p class="text-sm text-gray-500">Số lượng: {{ $detail->quantity }}</p>
                                        </div>
                                    </div>
                                    <p class="font-semibold text-gray-800">{{ number_format($detail->subtotal) }}đ</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 flex flex-col gap-2 text-sm text-gray-600 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <span class="font-medium text-gray-700">Địa chỉ:</span>
                                {{ $order->address ? $order->address->address_line.', '.$order->address->ward.', '.$order->address->district.', '.$order->address->province : 'Chưa cập nhật' }}
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Thanh toán:</span>
                                @php
                                    $paymentLabels = [
                                        'unpaid' => 'Chưa thanh toán',
                                        'paid' => 'Đã thanh toán',
                                        'refunded' => 'Hoàn tiền',
                                    ];
                                @endphp
                                {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
