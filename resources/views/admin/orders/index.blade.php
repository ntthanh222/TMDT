@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium uppercase tracking-[0.2em] text-emerald-600">Đơn hàng</p>
            <h1 class="mt-1 text-3xl font-bold text-gray-900">Quản Lý Đơn Hàng</h1>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                <thead class="bg-gray-50 text-xs font-semibold uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-4 py-3">Mã đơn</th>
                        <th class="px-4 py-3">Khách hàng</th>
                        <th class="px-4 py-3">Sản phẩm</th>
                        <th class="px-4 py-3">Tổng tiền</th>
                        <th class="px-4 py-3">Trạng thái</th>
                        <th class="px-4 py-3">Thanh toán</th>
                        <th class="px-4 py-3">Ngày đặt</th>
                        <th class="px-4 py-3 text-right">Cập nhật</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($orders as $order)
                        <tr class="hover:bg-gray-50/80">
                            <td class="px-4 py-3 font-medium text-gray-700">{{ $order->order_code }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800">{{ $order->user->name ?? 'Khách hàng' }}</div>
                                <div class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @foreach ($order->orderDetails as $detail)
                                    <div class="text-xs text-gray-700">{{ $detail->product_name }} x {{ $detail->quantity }}</div>
                                @endforeach
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ number_format($order->total) }}đ</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusMap = [
                                        'pending' => ['bg-amber-100', 'text-amber-800', 'Chờ xác nhận'],
                                        'confirmed' => ['bg-blue-100', 'text-blue-800', 'Đã xác nhận'],
                                        'shipping' => ['bg-indigo-100', 'text-indigo-800', 'Đang giao'],
                                        'completed' => ['bg-emerald-100', 'text-emerald-800', 'Hoàn thành'],
                                        'cancelled' => ['bg-red-100', 'text-red-800', 'Đã hủy'],
                                    ];
                                    $status = $statusMap[$order->status] ?? ['bg-gray-100', 'text-gray-800', 'Không xác định'];
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $status[0] }} {{ $status[1] }}">{{ $status[2] }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $paymentMap = [
                                        'unpaid' => ['bg-gray-100', 'text-gray-800', 'Chưa thanh toán'],
                                        'paid' => ['bg-emerald-100', 'text-emerald-800', 'Đã thanh toán'],
                                        'refunded' => ['bg-rose-100', 'text-rose-800', 'Hoàn tiền'],
                                    ];
                                    $payment = $paymentMap[$order->payment_status] ?? ['bg-gray-100', 'text-gray-800', 'Không xác định'];
                                @endphp
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $payment[0] }} {{ $payment[1] }}">{{ $payment[2] }}</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="flex items-center justify-end gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="rounded-lg border-gray-300 text-xs focus:border-emerald-500 focus:ring-emerald-500">
                                        @foreach (['pending' => 'Chờ xác nhận', 'confirmed' => 'Đã xác nhận', 'shipping' => 'Đang giao', 'completed' => 'Hoàn thành', 'cancelled' => 'Đã hủy'] as $value => $label)
                                            <option value="{{ $value }}" {{ $order->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <select name="payment_status" class="rounded-lg border-gray-300 text-xs focus:border-emerald-500 focus:ring-emerald-500">
                                        @foreach (['unpaid' => 'Chưa thanh toán', 'paid' => 'Đã thanh toán', 'refunded' => 'Hoàn tiền'] as $value => $label)
                                            <option value="{{ $value }}" {{ $order->payment_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">Lưu</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">Chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
