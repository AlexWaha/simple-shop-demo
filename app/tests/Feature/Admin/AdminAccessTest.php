<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('prevents non-admin access to dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertForbidden();
});

it('allows admin access to dashboard', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get('/dashboard');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('Admin/Dashboard')
    );
});

it('prevents non-admin access to admin products', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->get('/dashboard/products');

    $response->assertForbidden();
});

it('allows admin access to admin products', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get('/dashboard/products');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('Admin/Products/Index')
    );
});

it('prevents non-admin access to admin orders', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $response = $this->actingAs($user)->get('/dashboard/orders');

    $response->assertForbidden();
});

it('allows admin access to admin orders', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->get('/dashboard/orders');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('Admin/Orders/Index')
    );
});

it('requires authentication for dashboard', function () {
    $response = $this->get('/dashboard');

    $response->assertRedirect('/login');
});
