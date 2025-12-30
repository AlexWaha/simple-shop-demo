<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Jobs\SendOrderConfirmationJob;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class CheckoutController extends Controller
{
    public function show(Request $request): Response|RedirectResponse
    {
        $cart = $request->user()->cart;

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $cart->load('items.product');

        return Inertia::render('checkout/Show', [
            'cart' => (new CartResource($cart))->resolve(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = $request->user()->cart;

        if (! $cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $cart->load('items.product');

        // Validate stock availability
        foreach ($cart->items as $item) {
            if (! $item->product->hasStock($item->quantity)) {
                return back()->with('error', "Insufficient stock for {$item->product->name}. Available: {$item->product->stock_quantity}");
            }
        }

        try {
            $order = DB::transaction(function () use ($cart, $request) {
                // Create order
                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'status' => 'completed',
                    'total' => $cart->total,
                ]);

                // Create order items and update stock
                foreach ($cart->items as $item) {
                    $order->items()->create([
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'total' => $item->subtotal,
                    ]);

                    // Decrement stock
                    $item->product->decrementStock($item->quantity);
                }

                // Clear cart
                $cart->clear();

                return $order;
            });

            // Send order confirmation email to user
            SendOrderConfirmationJob::dispatch($order);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');
        } catch (Throwable $e) {
            Log::error('Checkout failed: '.$e->getMessage(), [
                'user_id' => $request->user()->id,
                'exception' => $e,
            ]);

            return back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }
}
