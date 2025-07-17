<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample products with realistic data
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with A17 Pro chip, titanium design, and advanced camera system.',
                'price' => 999.99,
                'stock_quantity' => 50,
                'category_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400',
                'sku' => 'IPHONE-15-PRO-001',
                'is_active' => true,
            ],
            [
                'name' => 'MacBook Air M3',
                'description' => 'Powerful laptop with M3 chip, 13-inch display, and all-day battery life.',
                'price' => 1299.99,
                'stock_quantity' => 25,
                'category_id' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400',
                'sku' => 'MACBOOK-AIR-M3-001',
                'is_active' => true,
            ],
            [
                'name' => 'AirPods Pro 2',
                'description' => 'Premium wireless earbuds with active noise cancellation and spatial audio.',
                'price' => 249.99,
                'stock_quantity' => 100,
                'category_id' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1606220945770-b5b6c2c55bf1?w=400',
                'sku' => 'AIRPODS-PRO-2-001',
                'is_active' => true,
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Android flagship with AI features, excellent camera, and premium design.',
                'price' => 899.99,
                'stock_quantity' => 75,
                'category_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400',
                'sku' => 'GALAXY-S24-001',
                'is_active' => true,
            ],
            [
                'name' => 'iPad Pro 12.9"',
                'description' => 'Professional tablet with M2 chip, Liquid Retina XDR display, and Apple Pencil support.',
                'price' => 1099.99,
                'stock_quantity' => 30,
                'category_id' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=400',
                'sku' => 'IPAD-PRO-12-001',
                'is_active' => true,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Industry-leading noise canceling headphones with exceptional sound quality.',
                'price' => 399.99,
                'stock_quantity' => 60,
                'category_id' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1583394838336-acd977736f90?w=400',
                'sku' => 'SONY-WH1000XM5-001',
                'is_active' => true,
            ],
            [
                'name' => 'Dell XPS 13',
                'description' => 'Premium ultrabook with Intel Core i7, 16GB RAM, and stunning InfinityEdge display.',
                'price' => 1199.99,
                'stock_quantity' => 20,
                'category_id' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=400',
                'sku' => 'DELL-XPS13-001',
                'is_active' => true,
            ],
            [
                'name' => 'Apple Watch Series 9',
                'description' => 'Advanced smartwatch with health monitoring, fitness tracking, and cellular connectivity.',
                'price' => 429.99,
                'stock_quantity' => 80,
                'category_id' => 4,
                'image_url' => 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?w=400',
                'sku' => 'APPLE-WATCH-S9-001',
                'is_active' => true,
            ],
            [
                'name' => 'Nintendo Switch OLED',
                'description' => 'Gaming console with vibrant OLED screen, enhanced audio, and versatile play modes.',
                'price' => 349.99,
                'stock_quantity' => 45,
                'category_id' => 5,
                'image_url' => 'https://images.unsplash.com/photo-1606144042614-b2417e99c4e3?w=400',
                'sku' => 'NINTENDO-SWITCH-OLED-001',
                'is_active' => true,
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'description' => 'AI-powered Android phone with exceptional camera and pure Google experience.',
                'price' => 999.99,
                'stock_quantity' => 0, // Out of stock
                'category_id' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400',
                'sku' => 'PIXEL-8-PRO-001',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create additional random products using factory
        Product::factory(20)->create();
        
        // Create some out of stock products
        Product::factory(5)->outOfStock()->create();
        
        // Create some inactive products
        Product::factory(3)->inactive()->create();
    }
}
