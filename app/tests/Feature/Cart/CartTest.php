<?php

use App\Models\Product;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('requires authentication to view cart', function () {
    $response = $this->get('/cart');

    $response->assertRedirect('/login');
});

it('displays empty cart for new user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/cart');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('cart/Index')
        ->has('cart')
    );
});

it('adds product to cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 10]);

    $response = $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('cart_items', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);
});

it('prevents adding more than available stock', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 5]);

    $response = $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 10,
    ]);

    $response->assertSessionHasErrors('quantity');
});

it('updates cart item quantity', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 10]);

    $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $user->refresh();
    $cartItem = $user->cart->items->first();

    $response = $this->actingAs($user)->patch("/cart/{$cartItem->id}", [
        'quantity' => 5,
    ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('cart_items', [
        'id' => $cartItem->id,
        'quantity' => 5,
    ]);
});

it('removes item from cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 10]);

    $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $user->refresh();
    $cartItem = $user->cart->items->first();

    $response = $this->actingAs($user)->delete("/cart/{$cartItem->id}");

    $response->assertRedirect();

    $this->assertDatabaseMissing('cart_items', [
        'id' => $cartItem->id,
    ]);
});
