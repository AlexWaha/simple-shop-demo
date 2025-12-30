<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminOrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Orders/Index', [
            'orders' => Order::query()
                ->with(['user', 'items'])
                ->latest()
                ->paginate(10)
                ->through(fn (Order $order) => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_name' => $order->user->name,
                    'user_email' => $order->user->email,
                    'status' => $order->status,
                    'total' => $order->total,
                    'formatted_total' => number_format($order->total, 2),
                    'items_count' => $order->items->count(),
                    'created_at' => $order->created_at->format('M d, Y H:i'),
                ]),
        ]);
    }

    public function show(Order $order): Response
    {
        $order->load(['user', 'items.product']);

        return Inertia::render('Admin/Orders/Show', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total' => $order->total,
                'formatted_total' => number_format($order->total, 2),
                'created_at' => $order->created_at->format('M d, Y H:i'),
                'user' => [
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'items' => $order->items->map(fn ($item) => [
                    'id' => $item->id,
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'price' => number_format($item->unit_price, 2),
                    'total' => number_format($item->subtotal, 2),
                ]),
            ],
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,completed,cancelled'],
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated successfully.');
    }
}
