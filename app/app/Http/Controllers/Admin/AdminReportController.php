<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AdminReportController extends Controller
{
    public function index(): Response
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        // Statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::where('stock_quantity', '<=', config('shop.low_stock_threshold', 5))->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_users' => User::where('is_admin', false)->count(),
            'today_orders' => Order::where('created_at', '>=', $today)->count(),
            'today_revenue' => Order::where('created_at', '>=', $today)
                ->where('status', 'completed')
                ->sum('total'),
            'month_revenue' => Order::where('created_at', '>=', $thisMonth)
                ->where('status', 'completed')
                ->sum('total'),
        ];

        // Recent orders
        $recentOrders = Order::query()
            ->with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Order $order) => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'user_name' => $order->user->name,
                'status' => $order->status,
                'total' => number_format($order->total, 2),
                'created_at' => $order->created_at->format('M d, Y H:i'),
            ]);

        // Low stock products
        $lowStockProducts = Product::query()
            ->where('stock_quantity', '<=', config('shop.low_stock_threshold', 5))
            ->orderBy('stock_quantity')
            ->take(5)
            ->get()
            ->map(fn (Product $product) => [
                'id' => $product->id,
                'name' => $product->name,
                'stock_quantity' => $product->stock_quantity,
            ]);

        // Daily sales for the last 7 days
        $dailySales = Order::query()
            ->where('status', 'completed')
            ->where('created_at', '>=', now()->subDays(7)->startOfDay())
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($day) => [
                'date' => $day->date,
                'orders_count' => $day->orders_count,
                'revenue' => number_format($day->revenue, 2),
            ]);

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'dailySales' => $dailySales,
        ]);
    }
}
