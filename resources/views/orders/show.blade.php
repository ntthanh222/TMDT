<x-app-layout>
    <div class="mx-auto max-w-5xl px-4 py-8">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Chi tiết đơn hàng</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $order->order_code }}</p>
            </div>
            <a href="{{ route('orders.history') }}" class="text-sm font-medium text-amber-700 hover:text-amber-800">← Quay lại lịch sử</a>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
            <div class="grid gap-4 border-b border-gray-200 pb-5 md:grid-cols-4">
                <div>
                    <p class="text-sm text-gray-500">Ngày đặt</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tổng tiền</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ number_format($order->total) }}đ</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Trạng thái</p>
                    <p class="mt-1 font-semibold text-gray-800">
                        @php
                            $statusLabels = [
                                'pending' => 'Chờ xác nhận',
                                'confirmed' => 'Đã xác nhận',
                                'shipping' => 'Đang giao',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy',
                            ];
                        @endphp
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Thanh toán</p>
                    <p class="mt-1 font-semibold text-gray-800">
                        @php
                            $paymentLabels = [
                                'unpaid' => 'Chưa thanh toán',
                                'paid' => 'Đã thanh toán',
                                'refunded' => 'Hoàn tiền',
                            ];
                        @endphp
                        {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <div>
                    <h2 class="mb-3 text-lg font-semibold text-gray-800">Sản phẩm</h2>
                    <div class="space-y-3">
                        @foreach ($order->orderDetails as $detail)
                            <div class="flex items-center justify-between gap-3 rounded-lg border border-gray-100 bg-gray-50 p-3">
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
                </div>

                <div>
                    <h2 class="mb-3 text-lg font-semibold text-gray-800">Thông tin giao hàng</h2>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700">
                        @if ($order->address)
                            <p><span class="font-medium">Người nhận:</span> {{ $order->address->recipient_name }}</p>
                            <p><span class="font-medium">SĐT:</span> {{ $order->address->phone }}</p>
                            <p><span class="font-medium">Địa chỉ:</span> {{ $order->address->address_line }}, {{ $order->address->ward }}, {{ $order->address->district }}, {{ $order->address->province }}</p>
                        @else
                            <p>Chưa có thông tin địa chỉ.</p>
                        @endif

                        @if ($order->note)
                            <div class="mt-3 border-t border-gray-200 pt-3">
                                <p class="font-medium text-gray-800">Ghi chú:</p>
                                <p class="mt-1 whitespace-pre-line">{{ $order->note }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($order->status === 'pending')
                        <form method="POST" action="{{ route('orders.cancel', $order) }}" class="mt-5" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100">
                                Hủy đơn hàng
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
