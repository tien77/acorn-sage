<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;

class ProductSeederCommand extends Command
{
    protected $signature = 'products:seed';
    protected $description = 'Seed sample products and categories';

    public function handle()
    {
        $this->info('Seeding categories and products...');
        
        try {
            // Tạo categories
            $categories = [
                [
                    'name' => 'Điện thoại',
                    'description' => 'Điện thoại thông minh các loại',
                    'is_active' => true,
                    'sort_order' => 1
                ],
                [
                    'name' => 'Laptop',
                    'description' => 'Máy tính xách tay cao cấp',
                    'is_active' => true,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Phụ kiện',
                    'description' => 'Phụ kiện công nghệ',
                    'is_active' => true,
                    'sort_order' => 3
                ]
            ];
            
            foreach ($categories as $categoryData) {
                $category = Category::create($categoryData);
                $this->line("Created category: {$category->name}");
            }
            
            // Tạo products
            $products = [
                [
                    'name' => 'iPhone 15 Pro Max',
                    'description' => 'iPhone 15 Pro Max với chip A17 Pro mạnh mẽ, camera chuyên nghiệp và thiết kế titanium cao cấp.',
                    'short_description' => 'iPhone 15 Pro Max - Đỉnh cao công nghệ Apple',
                    'sku' => 'IP15PM-128',
                    'price' => 32990000,
                    'sale_price' => 29990000,
                    'stock_quantity' => 50,
                    'stock_status' => 'in_stock',
                    'status' => 'published',
                    'is_featured' => true,
                    'published_at' => now(),
                    'weight' => 0.221,
                    'dimensions' => '159.9 x 76.7 x 8.25 mm'
                ],
                [
                    'name' => 'Samsung Galaxy S24 Ultra',
                    'description' => 'Galaxy S24 Ultra với S Pen tích hợp, camera 200MP và màn hình Dynamic AMOLED 2X 6.8 inch.',
                    'short_description' => 'Samsung Galaxy S24 Ultra - Siêu phẩm Android',
                    'sku' => 'SS24U-256',
                    'price' => 31990000,
                    'stock_quantity' => 30,
                    'stock_status' => 'in_stock',
                    'status' => 'published',
                    'is_featured' => true,
                    'published_at' => now(),
                    'weight' => 0.233,
                    'dimensions' => '162.3 x 79.0 x 8.6 mm'
                ],
                [
                    'name' => 'MacBook Pro 14 inch M3',
                    'description' => 'MacBook Pro 14 inch với chip M3 mạnh mẽ, màn hình Liquid Retina XDR và thời lượng pin lên đến 22 giờ.',
                    'short_description' => 'MacBook Pro 14 inch M3 - Máy tính của chuyên gia',
                    'sku' => 'MBP14-M3-512',
                    'price' => 52990000,
                    'stock_quantity' => 20,
                    'stock_status' => 'in_stock',
                    'status' => 'published',
                    'is_featured' => true,
                    'published_at' => now(),
                    'weight' => 1.55,
                    'dimensions' => '312.6 x 221.2 x 15.5 mm'
                ],
                [
                    'name' => 'AirPods Pro (2nd generation)',
                    'description' => 'AirPods Pro thế hệ 2 với chip H2, khử tiếng ồn chủ động cải tiến và âm thanh không gian cá nhân hóa.',
                    'short_description' => 'AirPods Pro Gen 2 - Trải nghiệm âm thanh đỉnh cao',
                    'sku' => 'APP-2ND-USB',
                    'price' => 6290000,
                    'stock_quantity' => 100,
                    'stock_status' => 'in_stock',
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now(),
                    'weight' => 0.051,
                    'dimensions' => '30.9 x 21.8 x 24.0 mm'
                ],
                [
                    'name' => 'iPad Air 11 inch M2',
                    'description' => 'iPad Air với chip M2 mạnh mẽ, màn hình Liquid Retina 11 inch và hỗ trợ Apple Pencil Pro.',
                    'short_description' => 'iPad Air M2 - Mỏng nhẹ, mạnh mẽ',
                    'sku' => 'IPA11-M2-128',
                    'price' => 16990000,
                    'stock_quantity' => 40,
                    'stock_status' => 'in_stock',
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now(),
                    'weight' => 0.462,
                    'dimensions' => '247.6 x 178.5 x 6.1 mm'
                ]
            ];
            
            $phoneCategory = Category::where('name', 'Điện thoại')->first();
            $laptopCategory = Category::where('name', 'Laptop')->first();
            $accessoryCategory = Category::where('name', 'Phụ kiện')->first();
            
            foreach ($products as $index => $productData) {
                $product = Product::create($productData);
                
                // Gán categories
                switch ($index) {
                    case 0: // iPhone
                    case 1: // Samsung
                        $product->categories()->attach([$phoneCategory->id]);
                        break;
                    case 2: // MacBook
                        $product->categories()->attach([$laptopCategory->id]);
                        break;
                    case 3: // AirPods
                        $product->categories()->attach([$accessoryCategory->id, $phoneCategory->id]);
                        break;
                    case 4: // iPad
                        $product->categories()->attach([$laptopCategory->id, $accessoryCategory->id]);
                        break;
                }
                
                $this->line("Created product: {$product->name}");
            }
            
            $this->info('Successfully seeded products and categories!');
            $this->info('Total categories: ' . Category::count());
            $this->info('Total products: ' . Product::count());
            
        } catch (\Exception $e) {
            $this->error('Error seeding data: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}