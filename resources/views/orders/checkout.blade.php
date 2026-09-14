<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Thanh toán đơn hàng</h1>
            <p class="mt-1 text-sm text-gray-500">Vui lòng kiểm tra thông tin giao hàng và chọn phương thức thanh toán.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Shipping Address Card --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-200 space-y-4">
                        <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">1. Thông tin giao hàng</h2>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Họ và tên người nhận <span class="text-rose-500">*</span></label>
                                <input type="text" name="recipient_name" value="{{ old('recipient_name', $defaultAddress?->recipient_name ?? auth()->user()->name) }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Số điện thoại <span class="text-rose-500">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', $defaultAddress?->phone ?? auth()->user()->phone ?? '0909123456') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Tỉnh / Thành phố <span class="text-rose-500">*</span></label>
                                <input type="text" name="province" value="{{ old('province', $defaultAddress?->province ?? 'Thành phố Hồ Chí Minh') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Quận / Huyện <span class="text-rose-500">*</span></label>
                                <input type="text" name="district" value="{{ old('district', $defaultAddress?->district ?? 'Quận 1') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Phường / Xã <span class="text-rose-500">*</span></label>
                                <input type="text" name="ward" value="{{ old('ward', $defaultAddress?->ward ?? 'Phường Bến Nghé') }}" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" required>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Địa chỉ chi tiết <span class="text-rose-500">*</span></label>
                            <textarea name="address_line" rows="2" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Số nhà, tên đường..." required>{{ old('address_line', $defaultAddress?->address_line ?? '72 Lê Thánh Tôn') }}</textarea>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Ghi chú giao hàng (không bắt buộc)</label>
                            <textarea name="note" rows="2" class="w-full rounded-lg border-gray-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="Ví dụ: Giao vào giờ hành chính...">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    {{-- Payment Method Card --}}
                    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-200 space-y-4">
                        <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">2. Phương thức thanh toán</h2>

                        <div class="space-y-3">
                            <label class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 cursor-pointer hover:border-emerald-500 transition">
                                <input type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }} class="mt-1 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="font-semibold text-gray-900 block text-sm">Thanh toán khi nhận hàng (COD)</span>
                                    <span class="text-xs text-gray-500">Thanh toán bằng tiền mặt trực tiếp cho shipper khi nhận được hàng.</span>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-4 rounded-xl border border-gray-200 cursor-pointer hover:border-emerald-500 transition">
                                <input type="radio" name="payment_method" value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }} class="mt-1 text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <span class="font-semibold text-gray-900 block text-sm">Chuyển khoản ngân hàng</span>
                                    <span class="text-xs text-gray-500">Chuyển khoản trực tiếp qua ngân hàng hoặc quét mã QR.</span>
                                    <div class="mt-2 text-xs bg-emerald-50 text-emerald-800 p-2.5 rounded-lg border border-emerald-100">
                                        <strong>Thông tin chuyển khoản:</strong><br>
                                        • Ngân hàng: <strong>MBBank</strong><br>
                                        • Số tài khoản: <strong>9999 GOODCAFE</strong><br>
                                        • Tên tài khoản: <strong>CÔNG TY TNHH GOODCAFE</strong>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Order Summary Sidebar --}}
                <aside class="space-y-6">
                    <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-200 space-y-4">
                        <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Đơn hàng của bạn</h2>
                        <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto">
                            @foreach ($items as $item)
                                <div class="flex gap-3 py-3">
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-14 w-14 rounded-lg object-cover border">
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm text-gray-900 line-clamp-1">{{ $item->product->name }}</p>
                                        <p class="text-xs text-gray-500">SL: {{ $item->quantity }} x {{ number_format($item->price) }}đ</p>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900">{{ number_format($item->quantity * $item->price) }}đ</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600">
                                <span>Tạm tính</span>
                                <span class="font-semibold text-gray-900">{{ number_format($subtotal) }}đ</span>
                            </div>

                            @if ($discountAmount > 0)
                                <div class="flex justify-between text-emerald-600">
                                    <span>Giảm giá ({{ $couponSession['code'] ?? '' }})</span>
                                    <span class="font-semibold">-{{ number_format($discountAmount) }}đ</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-gray-600">
                                <span>Phí vận chuyển</span>
                                <span class="text-emerald-600 font-semibold">Miễn phí</span>
                            </div>

                            <div class="border-t border-gray-200 pt-3 flex justify-between text-base font-bold text-gray-900">
                                <span>Tổng cộng</span>
                                <span class="text-emerald-600 text-xl">{{ number_format($total) }}đ</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 rounded-xl transition shadow-md">
                            Xác nhận đặt hàng →
                        </button>
                    </div>
                </aside>
            </div>
        </form>
    </div>
</x-app-layout>
