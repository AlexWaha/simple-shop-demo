<?php

use App\Jobs\SendOrderConfirmationJob;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('requires authentication for checkout', function () {
    $response = $this->get('/checkout');

    $response->assertRedirect('/login');
});

it('redirects to cart if cart is empty', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/checkout');

    $response->assertRedirect('/cart');
});

it('displays checkout page with cart items', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 29.99, 'stock_quantity' => 10]);

    $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($user->fresh())->get('/checkout');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('checkout/Show')
        ->has('cart')
    );
});

it('creates order on successful checkout', function () {
    Queue::fake();

    $user = User::factory()->create();
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'price' => 29.99,
        'stock_quantity' => 10,
    ]);

    $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $response = $this->actingAs($user->fresh())->post('/checkout');

    $response->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'status' => 'completed',
    ]);

    $this->assertDatabaseHas('order_items', [
        'product_name' => 'Test Product',
        'quantity' => 2,
    ]);

    // Verify stock was decremented
    $product->refresh();
    expect($product->stock_quantity)->toBe(8);
});

it('clears cart after successful checkout', function () {
    Queue::fake();

    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 10]);

    $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $this->actingAs($user->fresh())->post('/checkout');

    expect($user->fresh()->cart->items)->toBeEmpty();
});

it('dispatches order confirmation job after checkout', function () {
    Queue::fake();

    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 10]);

    $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 2,
    ]);

    $this->actingAs($user->fresh())->post('/checkout');

    Queue::assertPushed(SendOrderConfirmationJob::class);
});

it('prevents checkout with insufficient stock', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock_quantity' => 5]);

    // Add 3 items to cart
    $this->actingAs($user)->post('/cart', [
        'product_id' => $product->id,
        'quantity' => 3,
    ]);

    // Reduce stock to 2 (simulating another purchase)
    $product->update(['stock_quantity' => 2]);

    $response = $this->actingAs($user)->post('/checkout');

    $response->assertRedirect();
    $response->assertSessionHas('error');

    // No order should be created
    $this->assertDatabaseMissing('orders', [
        'user_id' => $user->id,
    ]);
});
