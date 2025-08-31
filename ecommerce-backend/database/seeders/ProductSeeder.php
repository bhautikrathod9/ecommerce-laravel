<?php
// database/seeders/ProductSeeder.php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with titanium design and A17 Pro chip',
                'price' => 999.00,
                'stock' => 50,
                'image_url' => 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Flagship Android smartphone with AI features',
                'price' => 899.00,
                'stock' => 30,
                'image_url' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'MacBook Air M3',
                'description' => 'Ultra-thin laptop with M3 chip and all-day battery',
                'price' => 1299.00,
                'stock' => 25,
                'image_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Premium noise-canceling wireless headphones',
                'price' => 349.00,
                'stock' => 75,
                'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'iPad Pro 12.9"',
                'description' => 'Powerful tablet with M2 chip and Liquid Retina display',
                'price' => 1099.00,
                'stock' => 20,
                'image_url' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Apple Watch Series 9',
                'description' => 'Advanced smartwatch with health monitoring',
                'price' => 399.00,
                'stock' => 60,
                'image_url' => 'https://images.unsplash.com/photo-1434494878577-86c23bcb06b9?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Dell XPS 13',
                'description' => 'Premium Windows laptop with InfinityEdge display',
                'price' => 1199.00,
                'stock' => 15,
                'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'AirPods Pro 2',
                'description' => 'Wireless earbuds with active noise cancellation',
                'price' => 249.00,
                'stock' => 100,
                'image_url' => 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Nintendo Switch OLED',
                'description' => 'Hybrid gaming console with vibrant OLED screen',
                'price' => 349.00,
                'stock' => 40,
                'image_url' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'description' => 'Android phone with advanced AI photography',
                'price' => 799.00,
                'stock' => 35,
                'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}