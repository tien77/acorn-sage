<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo categories trước
        $categories = [
            [
                'name' => 'Điện thoại',
                'slug' => 'dien-thoai',
                'description' => 'Điện thoại thông minh các loại',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Laptop',
                'slug' => 'laptop', 
                'description' => 'Máy tính xách tay',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Phụ kiện',
                'slug' => 'phu-kien',
                'description' => 'Phụ kiện công nghệ',
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Tạo sản phẩm mẫu
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'iPhone 15 Pro với chip A17 Pro mạnh mẽ',
                'short_description' => 'Điện thoại iPhone cao cấp mới nhất',
                'sku' => 'IP15PRO001',
                'price' => 29990000,
                'sale_price' => 27990000,
                'stock_quantity' => 50,
                'manage_stock' => true,
                'stock_status' => 'in_stock',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now(),
                'category' => 'dien-thoai'
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'slug' => 'samsung-galaxy-s24',
                'description' => 'Galaxy S24 với AI tích hợp thông minh',
                'short_description' => 'Smartphone Android hàng đầu',
                'sku' => 'SS24001',
                'price' => 24990000,
                'sale_price' => null,
                'stock_quantity' => 30,
                'manage_stock' => true,
                'stock_status' => 'in_stock',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
                'category' => 'dien-thoai'
            ],
            [
                'name' => 'MacBook Air M3',
                'slug' => 'macbook-air-m3',
                'description' => 'MacBook Air với chip M3 siêu nhanh và tiết kiệm pin',
                'short_description' => 'Laptop Apple mỏng nhẹ',
                'sku' => 'MBA3001',
                'price' => 32990000,
                'sale_price' => 31490000,
                'stock_quantity' => 25,
                'manage_stock' => true,
                'stock_status' => 'in_stock',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now(),
                'category' => 'laptop'
            ],
            [
                'name' => 'Dell XPS 13',
                'slug' => 'dell-xps-13',
                'description' => 'Laptop Dell XPS 13 với thiết kế cao cấp và hiệu năng mạnh mẽ',
                'short_description' => 'Laptop Windows cao cấp',
                'sku' => 'DXPS13001',
                'price' => 28990000,
                'sale_price' => null,
                'stock_quantity' => 15,
                'manage_stock' => true,
                'stock_status' => 'in_stock',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
                'category' => 'laptop'
            ],
            [
                'name' => 'AirPods Pro 2',
                'slug' => 'airpods-pro-2',
                'description' => 'Tai nghe AirPods Pro thế hệ 2 với chống ồn chủ động',
                'short_description' => 'Tai nghe không dây cao cấp',
                'sku' => 'APP2001',
                'price' => 6490000,
                'sale_price' => 5990000,
                'stock_quantity' => 100,
                'manage_stock' => true,
                'stock_status' => 'in_stock',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now(),
                'category' => 'phu-kien'
            ],
            [
                'name' => 'Sạc nhanh 67W',
                'slug' => 'sac-nhanh-67w',
                'description' => 'Sạc nhanh 67W tương thích với nhiều thiết bị',
                'short_description' => 'Sạc nhanh đa năng',
                'sku' => 'CHG67W001',
                'price' => 590000,
                'sale_price' => null,
                'stock_quantity' => 0, // Hết hàng để test
                'manage_stock' => true,
                'stock_status' => 'out_of_stock',
                'status' => 'published',
                'is_featured' => false,
                'published_at' => now(),
                'category' => 'phu-kien'
            ]
        ];

        foreach ($products as $productData) {
            $categorySlug = $productData['category'];
            unset($productData['category']);

            $product = Product::firstOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );

            // Gán category
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $product->categories()->syncWithoutDetaching([$category->id]);
            }
        }
    }
}