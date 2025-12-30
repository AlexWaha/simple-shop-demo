<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Teddy Bear',
                'description' => 'Soft and cuddly plush teddy bear, perfect for children of all ages.',
                'price' => 19.99,
                'stock_quantity' => 25,
                'source_image' => 'teddy-bear.jpg',
            ],
            [
                'name' => 'LEGO Classic Building Set',
                'description' => 'Creative building blocks set with 500+ pieces for endless fun.',
                'price' => 34.99,
                'stock_quantity' => 3,
                'source_image' => 'lego-blocks.jpg',
            ],
            [
                'name' => 'Baby Doll',
                'description' => 'Interactive baby doll with accessories and realistic features.',
                'price' => 29.99,
                'stock_quantity' => 0,
                'source_image' => 'baby-doll.jpg',
            ],
            [
                'name' => 'Hot Wheels Car Set',
                'description' => 'Pack of 5 die-cast racing cars with exciting designs.',
                'price' => 9.99,
                'stock_quantity' => 50,
                'source_image' => 'toy-cars.jpg',
            ],
            [
                'name' => 'Monopoly Board Game',
                'description' => 'Classic family board game for 2-8 players.',
                'price' => 24.99,
                'stock_quantity' => 2,
                'source_image' => 'board-game.jpg',
            ],
            [
                'name' => 'Jigsaw Puzzle 1000 Pieces',
                'description' => 'Beautiful landscape puzzle for hours of entertainment.',
                'price' => 14.99,
                'stock_quantity' => 30,
                'source_image' => 'puzzle.jpg',
            ],
            [
                'name' => 'RC Helicopter',
                'description' => 'Remote controlled helicopter with LED lights and gyro stabilization.',
                'price' => 49.99,
                'stock_quantity' => 5,
                'source_image' => 'rc-helicopter.jpg',
            ],
            [
                'name' => 'Art Drawing Set',
                'description' => 'Complete art set with colored pencils, markers, and crayons.',
                'price' => 12.99,
                'stock_quantity' => 40,
                'source_image' => 'art-supplies.jpg',
            ],
            [
                'name' => 'Soccer Ball',
                'description' => 'Official size 5 soccer ball for outdoor play.',
                'price' => 15.99,
                'stock_quantity' => 0,
                'source_image' => 'soccer-ball.jpg',
            ],
            [
                'name' => 'Transformer Robot',
                'description' => 'Convertible robot toy that transforms into a vehicle.',
                'price' => 22.99,
                'stock_quantity' => 18,
                'source_image' => 'transformer-robot.jpg',
            ],
        ];

        Storage::disk('public')->makeDirectory('products');

        foreach ($products as $productData) {
            $sourceImage = $productData['source_image'];
            unset($productData['source_image']);

            $sourcePath = public_path("images/products/{$sourceImage}");
            if (File::exists($sourcePath)) {
                $storagePath = "products/{$sourceImage}";
                Storage::disk('public')->put($storagePath, File::get($sourcePath));
                $productData['image'] = $storagePath;
            }

            Product::create($productData);
        }
    }
}
