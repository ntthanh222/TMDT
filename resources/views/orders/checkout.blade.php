<x-app-layout>
    <div class="mx-auto max-w-6xl px-4 py-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Thanh toán</h1>
            <p class="mt-1 text-sm text-gray-500">Kiểm tra đơn hàng và xác nhận thông tin giao hàng.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h2 class="mb-5 text-lg font-semibold text-gray-800">Thông tin giao hàng</h2>

                    <form method="POST" action="{{ route('orders.store') }}" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Tên người nhận</label>
                                <input type="text" name="recipient_name" value="{{ old('recipient_name', $defaultAddress?->recipient_name ?? auth()->user()->name) }}" class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ old('phone', $defaultAddress?->phone ?? auth()->user()->phone) }}" class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Tỉnh/Thành</label>
                                <input type="text" name="province" value="{{ old('province', $defaultAddress?->province) }}" class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Quận/Huyện</label>
                                <input type="text" name="district" value="{{ old('district', $defaultAddress?->district) }}" class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700">Phường/Xã</label>
                                <input type="text" name="ward" value="{{ old('ward', $defaultAddress?->ward) }}" class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500" required>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Địa chỉ chi tiết</label>
                            <textarea name="address_line" rows="3" class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500" required>{{ old('address_line', $defaultAddress?->address_line) }}</textarea>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700">Ghi chú</label>
                            <textarea name="note" rows="3" class="w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500" placeholder="Ví dụ: Giao giờ hành chính...">{{ old('note') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="rounded-lg bg-amber-600 px-5 py-3 text-sm font-semibold text-white hover:bg-amber-700">
                                Xác nhận đặt hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-200">
                    <h2 class="mb-4 text-lg font-semibold text-gray-800">Đơn hàng</h2>
                    <div class="space-y-4">
                        @foreach ($items as $item)
                            <div class="flex gap-3 border-b border-gray-100 pb-3 last:border-b-0 last:pb-0">
                                <img src="{{ $item->product->image ? asset('storage/'.$item->product->image) : 'https://placehold.co/120x120?text=No+Image' }}" alt="{{ $item->product->name }}" class="h-16 w-16 rounded-md object-cover">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                                    <p class="text-sm text-gray-500">Số lượng: {{ $item->quantity }}</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ number_format($item->quantity * $item->price) }}đ</p>
                            </div>
                        @endforeach
                    </div>

                    @php
                        $subtotal = $items->sum(fn ($item) => $item->quantity * $item->price);
                    @endphp

                    <div class="mt-5 space-y-3 text-sm text-gray-600">
                        <div class="flex justify-between"><span>Tạm tính</span><span>{{ number_format($subtotal) }}đ</span></div>
                        <div class="flex justify-between"><span>Phí vận chuyển</span><span>0đ</span></div>
                        <div class="flex justify-between border-t border-gray-200 pt-3 text-base font-bold text-gray-800"><span>Tổng cộng</span><span>{{ number_format($subtotal) }}đ</span></div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
