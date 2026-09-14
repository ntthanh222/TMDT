<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', 1);

        // Tìm kiếm theo tên
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%'.$request->keyword.'%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo khoảng giá
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sắp xếp
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'best_selling':
                $query->withSum(['orderDetails as total_sold' => function ($q) {
                    $q->whereHas('order', function ($o) {
                        $o->whereIn('status', ['completed', 'confirmed', 'shipping', 'pending']);
                    });
                }], 'quantity')->orderByDesc('total_sold');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', 1)->get();

        return view('products.search', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (! $product->is_active) {
            abort(404);
        }

        $product->load(['category', 'images']);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        $approvedReviews = $product->reviews()->where('is_approved', true)->with('user')->latest()->get();
        $avgRating = round($approvedReviews->avg('rating') ?? 0, 1);

        return view('products.show', compact('product', 'relatedProducts', 'approvedReviews', 'avgRating'));
    }
}
