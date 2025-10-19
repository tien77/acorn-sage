<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các danh mục cha
        $electronics = Category::create([
            'name' => 'Điện tử',
            'slug' => 'dien-tu',
            'description' => 'Các sản phẩm điện tử, công nghệ',
            'is_active' => true,
            'sort_order' => 1
        ]);

        $fashion = Category::create([
            'name' => 'Thời trang',
            'slug' => 'thoi-trang',
            'description' => 'Quần áo, phụ kiện thời trang',
            'is_active' => true,
            'sort_order' => 2
        ]);

        $home = Category::create([
            'name' => 'Nhà cửa & Đời sống',
            'slug' => 'nha-cua-doi-song',
            'description' => 'Đồ dùng gia đình, nội thất',
            'is_active' => true,
            'sort_order' => 3
        ]);

        // Tạo các danh mục con cho Điện tử
        Category::create([
            'name' => 'Điện thoại',
            'slug' => 'dien-thoai',
            'description' => 'Smartphone, điện thoại di động',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'sort_order' => 1
        ]);

        Category::create([
            'name' => 'Laptop',
            'slug' => 'laptop',
            'description' => 'Máy tính xách tay',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'sort_order' => 2
        ]);

        Category::create([
            'name' => 'Tai nghe',
            'slug' => 'tai-nghe',
            'description' => 'Tai nghe, headphone',
            'parent_id' => $electronics->id,
            'is_active' => true,
            'sort_order' => 3
        ]);

        // Tạo các danh mục con cho Thời trang
        Category::create([
            'name' => 'Áo',
            'slug' => 'ao',
            'description' => 'Áo sơ mi, áo thun, áo khoác',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'sort_order' => 1
        ]);

        Category::create([
            'name' => 'Quần',
            'slug' => 'quan',
            'description' => 'Quần jean, quần tây, quần short',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'sort_order' => 2
        ]);

        Category::create([
            'name' => 'Giày dép',
            'slug' => 'giay-dep',
            'description' => 'Giày thể thao, giày tây, dép',
            'parent_id' => $fashion->id,
            'is_active' => true,
            'sort_order' => 3
        ]);

        // Tạo các danh mục con cho Nhà cửa
        Category::create([
            'name' => 'Nội thất',
            'slug' => 'noi-that',
            'description' => 'Bàn, ghế, tủ, giường',
            'parent_id' => $home->id,
            'is_active' => true,
            'sort_order' => 1
        ]);

        Category::create([
            'name' => 'Đồ gia dụng',
            'slug' => 'do-gia-dung',
            'description' => 'Đồ dùng bếp, đồ dùng sinh hoạt',
            'parent_id' => $home->id,
            'is_active' => true,
            'sort_order' => 2
        ]);
    }
}