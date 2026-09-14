<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = User::where('role', 'customer')->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalRevenue = Order::where('payment_status', 'paid')
            ->orWhere('status', 'completed')
            ->sum('total');

        $bestSellingProducts = Product::withSum(['orderDetails as total_sold' => function ($q) {
            $q->whereHas('order', function ($o) {
                $o->whereIn('status', ['completed', 'confirmed', 'shipping', 'pending']);
            });
        }], 'quantity')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalOrders',
            'pendingOrders',
            'totalRevenue',
            'bestSellingProducts',
            'recentOrders'
        ));
    }
}
