<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
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

        return view('cart.index', compact('cart', 'items', 'subtotal'));
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
