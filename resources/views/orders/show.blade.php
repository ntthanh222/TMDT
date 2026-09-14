<x-app-layout>
    <div class="mx-auto max-w-5xl px-4 py-8">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Chi tiết đơn hàng</h1>
                <p class="mt-1 text-sm text-gray-500">Mã đơn: <span class="font-mono font-bold text-emerald-600">{{ $order->order_code }}</span></p>
            </div>
            <a href="{{ route('orders.history') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">← Quay lại lịch sử</a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-200 space-y-6">
            <div class="grid gap-4 border-b border-gray-100 pb-5 md:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Ngày đặt</p>
                    <p class="mt-1 font-semibold text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Tổng thanh toán</p>
                    <p class="mt-1 font-bold text-emerald-600 text-lg">{{ number_format($order->total) }}đ</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Trạng thái đơn</p>
                    <span class="inline-flex mt-1 rounded-full px-2.5 py-0.5 text-xs font-semibold
                        @if($order->status === 'completed') bg-emerald-100 text-emerald-800
                        @elseif($order->status === 'cancelled') bg-rose-100 text-rose-800
                        @elseif($order->status === 'shipping') bg-blue-100 text-blue-800
                        @else bg-amber-100 text-amber-800 @endif">
                        @php
                            $statusLabels = [
                                'pending' => 'Chờ xác nhận',
                                'confirmed' => 'Đã xác nhận',
                                'shipping' => 'Đang giao hàng',
                                'completed' => 'Hoàn thành',
                                'cancelled' => 'Đã hủy',
                            ];
                        @endphp
                        {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Thanh toán</p>
                    <span class="inline-flex mt-1 rounded-full px-2.5 py-0.5 text-xs font-semibold
                        @if($order->payment_status === 'paid') bg-emerald-100 text-emerald-800
                        @elseif($order->payment_status === 'refunded') bg-purple-100 text-purple-800
                        @else bg-amber-100 text-amber-800 @endif">
                        @php
                            $paymentLabels = [
                                'unpaid' => 'Chưa thanh toán',
                                'paid' => 'Đã thanh toán',
                                'refunded' => 'Hoàn tiền',
                            ];
                        @endphp
                        {{ $paymentLabels[$order->payment_status] ?? ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <h2 class="mb-3 text-base font-bold text-gray-900">Sản phẩm mua</h2>
                    <div class="space-y-3">
                        @foreach ($order->orderDetails as $detail)
                            <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 bg-gray-50 p-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $detail->product ? $detail->product->image_url : asset('images/products/default.svg') }}" alt="{{ $detail->product_name }}" class="h-14 w-14 rounded-lg object-cover border">
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $detail->product_name }}</p>
                                        <p class="text-xs text-gray-500">Số lượng: {{ $detail->quantity }} x {{ number_format($detail->price) }}đ</p>
                                    </div>
                                </div>
                                <p class="font-bold text-gray-900 text-sm">{{ number_format($detail->subtotal) }}đ</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 border-t border-gray-100 pt-3 space-y-1.5 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span>Tạm tính</span>
                            <span>{{ number_format($order->subtotal) }}đ</span>
                        </div>
                        @if ($order->discount_amount > 0)
                            <div class="flex justify-between text-emerald-600">
                                <span>Giảm giá</span>
                                <span>-{{ number_format($order->discount_amount) }}đ</span>
                            </div>
                        @endif
                        <div class="flex justify-between font-bold text-gray-900 pt-2 border-t">
                            <span>Tổng tiền</span>
                            <span class="text-emerald-600">{{ number_format($order->total) }}đ</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-base font-bold text-gray-900">Thông tin giao hàng & Thanh toán</h2>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700 space-y-2">
                        @if ($order->address)
                            <p><span class="font-semibold text-gray-900">Người nhận:</span> {{ $order->address->recipient_name }}</p>
                            <p><span class="font-semibold text-gray-900">Số điện thoại:</span> {{ $order->address->phone }}</p>
                            <p><span class="font-semibold text-gray-900">Địa chỉ:</span> {{ $order->address->address_line }}, {{ $order->address->ward }}, {{ $order->address->district }}, {{ $order->address->province }}</p>
                        @endif

                        @if ($order->note)
                            <div class="mt-3 border-t border-gray-200 pt-2">
                                <p class="font-semibold text-gray-900">Ghi chú:</p>
                                <p class="text-gray-600 whitespace-pre-line">{{ $order->note }}</p>
                            </div>
                        @endif
                    </div>

                    @if ($order->payment_status === 'unpaid')
                        <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-900 space-y-1">
                            <p class="font-bold text-sm text-amber-800">Thông tin chuyển khoản ngân hàng:</p>
                            <p>• Ngân hàng: <strong>MBBank</strong></p>
                            <p>• Số tài khoản: <strong>9999 GOODCAFE</strong></p>
                            <p>• Số tiền: <strong>{{ number_format($order->total) }}đ</strong></p>
                            <p>• Nội dung chuyển khoản: <strong class="font-mono bg-white px-2 py-0.5 rounded border border-amber-300 text-amber-900">{{ $order->order_code }}</strong></p>
                        </div>
                    @endif

                    @if ($order->status === 'pending')
                        <form method="POST" action="{{ route('orders.cancel', $order) }}" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full rounded-xl border border-rose-200 bg-rose-50 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-100 transition">
                                Hủy đơn hàng này
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
