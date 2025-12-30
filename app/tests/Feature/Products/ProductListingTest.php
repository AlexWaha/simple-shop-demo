<?php

use App\Models\Product;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('displays products on homepage', function () {
    Product::factory()->count(3)->create();

    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('products/Index')
        ->has('products.data', 3)
    );
});

it('shows only active products', function () {
    Product::factory()->count(2)->create();
    Product::factory()->inactive()->create();

    $response = $this->get('/');

    $response->assertInertia(fn ($page) => $page
        ->has('products.data', 2)
    );
});

it('displays single product details', function () {
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'price' => 29.99,
    ]);

    $response = $this->get("/products/{$product->slug}");

    $response->assertSuccessful();
    $response->assertInertia(fn ($page) => $page
        ->component('products/Show')
        ->has('product')
    );
});

it('returns 404 for non-existent product', function () {
    $response = $this->get('/products/non-existent-product');

    $response->assertNotFound();
});
