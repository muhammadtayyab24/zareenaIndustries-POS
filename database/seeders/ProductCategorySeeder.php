<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Polyester Fiber', 'status' => 1],
            ['name' => 'Polyfill', 'status' => 1],
            ['name' => 'Fabric', 'status' => 1],
            ['name' => 'Thread', 'status' => 1],
            ['name' => 'Zipper', 'status' => 1],
            ['name' => 'Button', 'status' => 1],
            ['name' => 'Foam', 'status' => 1],
            ['name' => 'Cotton', 'status' => 1],
            ['name' => 'Pillow Cover', 'status' => 1],
            ['name' => 'Quilt Cover', 'status' => 1],
        ];

        foreach ($categories as $category) {
            ProductCategory::create([
                'name' => $category['name'],
                'status' => $category['status'],
                'is_deleted' => false,
            ]);
        }
    }
}
