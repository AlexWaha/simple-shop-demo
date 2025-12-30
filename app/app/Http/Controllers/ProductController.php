<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(12);

        return Inertia::render('products/Index', [
            'products' => ProductResource::collection($products),
        ]);
    }

    public function show(Product $product): Response
    {
        return Inertia::render('products/Show', [
            'product' => (new ProductResource($product))->resolve(),
        ]);
    }
}
