<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart()->with('items.product')->first();
        $items = $cart?->items ?? collect();
        $subtotal = $items->sum(fn ($item) => $item->quantity * $item->price);

        $coupon = session('coupon');
        $discount = $coupon ? (float) $coupon['discount'] : 0;
        $total = max(0, $subtotal - $discount);

        return view('cart.index', compact('cart', 'items', 'subtotal', 'coupon', 'discount', 'total'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $code = strtoupper(trim($request->coupon_code));
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return back()->with('error', 'Mã giảm giá không tồn tại.');
        }

        $cart = Auth::user()->cart()->with('items.product')->first();
        $items = $cart?->items ?? collect();
        $subtotal = $items->sum(fn ($item) => $item->quantity * $item->price);

        if (! $coupon->isValidForAmount($subtotal)) {
            if ($coupon->expires_at && now()->gt($coupon->expires_at)) {
                return back()->with('error', 'Mã giảm giá đã hết hạn sử dụng.');
            }
            if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
                return back()->with('error', 'Đơn hàng tối thiểu '.number_format($coupon->min_order_amount).'đ để dùng mã này.');
            }

            return back()->with('error', 'Mã giảm giá không hợp lệ hoặc đã hết lượt dùng.');
        }

        $discount = $coupon->calculateDiscount($subtotal);
        session([
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount' => $discount,
            ],
        ]);

        return back()->with('success', 'Áp dụng mã giảm giá thành công: -'.number_format($discount).'đ');
    }

    public function removeCoupon()
    {
        session()->forget('coupon');

        return back()->with('success', 'Đã hủy mã giảm giá.');
    }

    public function add(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = Auth::user()->cart()->firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        $item = $cart->items()->where('product_id', $product->id)->first();
        $newQuantity = ($item?->quantity ?? 0) + $validated['quantity'];

        if ($product->stock_quantity !== null && $newQuantity > (int) $product->stock_quantity) {
            return back()->withErrors([
                'quantity' => 'Số lượng vượt quá tồn kho của sản phẩm.',
            ])->withInput();
        }

        if ($item) {
            $item->quantity = $newQuantity;
            $item->price = $product->sale_price ?? $product->price;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'price' => $product->sale_price ?? $product->price,
            ]);
        }

        return redirect()->route('products.search')->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request, CartItem $item)
    {
        abort_unless($item->cart && $item->cart->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $product = $item->product;
        if ($product->stock_quantity !== null && $validated['quantity'] > (int) $product->stock_quantity) {
            return back()->withErrors([
                'quantity' => 'Số lượng vượt quá tồn kho của sản phẩm.',
            ]);
        }

        $item->quantity = $validated['quantity'];
        $item->price = $product->sale_price ?? $product->price;
        $item->save();

        return redirect()->route('cart.index')->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    public function remove(CartItem $item)
    {
        abort_unless($item->cart && $item->cart->user_id === Auth::id(), 403);

        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng.');
    }
}
