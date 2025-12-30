<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('requires authentication to view orders', function () {
    $response = $this->get('/orders');

    $response->assertRedirect('/login');
});

it('displays order history for authenticated user', function () {
    $user = User::factory()->create();
    Order::factory()->count(3)->for($user)->create();

    $response = $this->actingAs($user)->get('/orders');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Index')
        ->has('orders.data', 3)
    );
});

it('shows only own orders', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    Order::factory()->count(2)->for($user)->create();
    Order::factory()->count(3)->for($otherUser)->create();

    $response = $this->actingAs($user)->get('/orders');

    $response->assertInertia(fn ($page) => $page
        ->has('orders.data', 2)
    );
});

it('displays order details', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create();
    OrderItem::factory()->count(2)->for($order)->create();

    $response = $this->actingAs($user)->get("/orders/{$order->order_number}");

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('orders/Show')
        ->has('order')
    );
});

it('prevents viewing other users orders', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $order = Order::factory()->for($otherUser)->create();

    $response = $this->actingAs($user)->get("/orders/{$order->order_number}");

    $response->assertForbidden();
});
