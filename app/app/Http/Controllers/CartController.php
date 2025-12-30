<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        $cart = $request->user()->getOrCreateCart();
        $cart->load('items.product');

        return Inertia::render('cart/Index', [
            'cart' => (new CartResource($cart))->resolve(),
        ]);
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $cart = $request->user()->getOrCreateCart();
        $product = Product::findOrFail($request->validated('product_id'));

        $existingItem = $cart->items()->where('product_id', $product->id)->first();

        if ($existingItem) {
            $existingItem->increment('quantity', $request->validated('quantity'));
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->validated('quantity'),
                'price' => $product->price,
            ]);
        }

        return back()->with('success', 'Product added to cart.');
    }

    public function update(UpdateCartItemRequest $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorize('update', $cartItem);

        $cartItem->update(['quantity' => $request->validated('quantity')]);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Request $request, CartItem $cartItem): RedirectResponse
    {
        $this->authorize('delete', $cartItem);

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
