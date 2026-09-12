<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function checkout(): View
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items.product.category')->first();
        $items = $cart?->items ?? collect();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $defaultAddress = $user->addresses()->where('is_default', true)->first();

        return view('orders.checkout', compact('cart', 'items', 'defaultAddress'));
    }

    public function history(): View
    {
        $orders = Auth::user()
            ->orders()
            ->with(['address', 'orderDetails.product'])
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }

    public function show(Order $order): View
    {
        abort_unless($order->user_id === Auth::id(), 403);

        $order->load(['address', 'orderDetails.product']);

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);

        if ($order->status !== 'pending') {
            return redirect()->route('orders.history')->with('error', 'Chỉ có thể hủy đơn hàng ở trạng thái chờ xác nhận.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.history')->with('success', 'Đã hủy đơn hàng thành công.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = $user->cart()->with('items.product')->first();
        $items = $cart?->items ?? collect();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $validated = $request->validate([
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'province' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'ward' => ['required', 'string', 'max:255'],
            'address_line' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($items as $item) {
            $product = $item->product;
            if (! $product) {
                return redirect()->route('cart.index')->with('error', 'Một số sản phẩm trong giỏ hàng không còn tồn tại.');
            }

            if ($product->stock_quantity !== null && $item->quantity > (int) $product->stock_quantity) {
                return redirect()->route('cart.index')->with('error', 'Sản phẩm "'.$product->name.'" không đủ số lượng trong kho.');
            }
        }

        $subtotal = $items->sum(fn ($item) => (float) $item->quantity * (float) $item->price);
        $shippingFee = 0;
        $discountAmount = 0;
        $total = $subtotal + $shippingFee - $discountAmount;

        $address = $user->addresses()->create([
            'recipient_name' => $validated['recipient_name'],
            'phone' => $validated['phone'],
            'province' => $validated['province'],
            'district' => $validated['district'],
            'ward' => $validated['ward'],
            'address_line' => $validated['address_line'],
            'is_default' => ! $user->addresses()->exists(),
        ]);

        $orderCode = 'ORD-'.now()->format('YmdHis').'-'.random_int(1000, 9999);

        $order = DB::transaction(function () use ($user, $cart, $address, $subtotal, $shippingFee, $discountAmount, $total, $validated, $orderCode, $items) {
            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $user->id,
                'address_id' => $address->id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_fee' => $shippingFee,
                'total' => $total,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($items as $item) {
                $product = $item->product;

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'subtotal' => (float) $item->quantity * (float) $item->price,
                ]);

                if ($product->stock_quantity !== null) {
                    $product->decrement('stock_quantity', $item->quantity);
                }
            }

            $cart->items()->delete();

            return $order;
        });

        return redirect()->route('cart.index')->with('success', 'Đặt hàng thành công. Mã đơn hàng: '.$order->order_code);
    }
}
