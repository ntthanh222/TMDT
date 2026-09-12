<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Auth::user()->wishlists()->with('product')->latest()->get();

        return view('wishlist.index', compact('items'));
    }

    public function add(Product $product)
    {
        $exists = Auth::user()->wishlists()->where('product_id', $product->id)->exists();

        if (! $exists) {
            Auth::user()->wishlists()->create([
                'product_id' => $product->id,
            ]);
        }

        return redirect()->route('products.search')->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích.');
    }

    public function remove(Product $product)
    {
        Auth::user()->wishlists()->where('product_id', $product->id)->delete();

        return redirect()->route('wishlist.index')->with('success', 'Đã xoá sản phẩm khỏi danh sách yêu thích.');
    }
}
