<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories and types
        $polyesterCategory = ProductCategory::where('name', 'Polyester Fiber')->first();
        $polyfillCategory = ProductCategory::where('name', 'Polyfill')->first();
        $fabricCategory = ProductCategory::where('name', 'Fabric')->first();
        $threadCategory = ProductCategory::where('name', 'Thread')->first();
        $zipperCategory = ProductCategory::where('name', 'Zipper')->first();
        $buttonCategory = ProductCategory::where('name', 'Button')->first();
        $foamCategory = ProductCategory::where('name', 'Foam')->first();
        $cottonCategory = ProductCategory::where('name', 'Cotton')->first();
        $pillowCoverCategory = ProductCategory::where('name', 'Pillow Cover')->first();
        $quiltCoverCategory = ProductCategory::where('name', 'Quilt Cover')->first();

        $rawMaterialType = ProductType::where('name', 'Raw Material')->first();
        $finishedProductType = ProductType::where('name', 'Finished Product')->first();
        $accessoriesType = ProductType::where('name', 'Accessories')->first();
        $semiFinishedType = ProductType::where('name', 'Semi-Finished')->first();

        $products = [
            // Polyester Fiber Products
            [
                'cat_id' => $polyesterCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Polyester Fiber 6D',
                'unit_type' => 'KG',
                'opening_qty' => 1000,
                'current_qty' => 1000,
                'status' => 1,
            ],
            [
                'cat_id' => $polyesterCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Polyester Fiber 7D',
                'unit_type' => 'KG',
                'opening_qty' => 800,
                'current_qty' => 800,
                'status' => 1,
            ],
            [
                'cat_id' => $polyesterCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Polyester Fiber 15D',
                'unit_type' => 'KG',
                'opening_qty' => 500,
                'current_qty' => 500,
                'status' => 1,
            ],

            // Polyfill Products
            [
                'cat_id' => $polyfillCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Polyfill Standard',
                'unit_type' => 'KG',
                'opening_qty' => 2000,
                'current_qty' => 2000,
                'status' => 1,
            ],
            [
                'cat_id' => $polyfillCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Polyfill Premium',
                'unit_type' => 'KG',
                'opening_qty' => 1500,
                'current_qty' => 1500,
                'status' => 1,
            ],

            // Fabric Products
            [
                'cat_id' => $fabricCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Cotton Fabric White',
                'unit_type' => 'MTR',
                'opening_qty' => 500,
                'current_qty' => 500,
                'status' => 1,
            ],
            [
                'cat_id' => $fabricCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Polyester Fabric',
                'unit_type' => 'MTR',
                'opening_qty' => 600,
                'current_qty' => 600,
                'status' => 1,
            ],
            [
                'cat_id' => $fabricCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Bamboo Fabric',
                'unit_type' => 'MTR',
                'opening_qty' => 300,
                'current_qty' => 300,
                'status' => 1,
            ],

            // Thread Products
            [
                'cat_id' => $threadCategory->id,
                'type_id' => $accessoriesType->id,
                'product_name' => 'Polyester Thread White',
                'unit_type' => 'PCS',
                'opening_qty' => 100,
                'current_qty' => 100,
                'status' => 1,
            ],
            [
                'cat_id' => $threadCategory->id,
                'type_id' => $accessoriesType->id,
                'product_name' => 'Cotton Thread',
                'unit_type' => 'PCS',
                'opening_qty' => 80,
                'current_qty' => 80,
                'status' => 1,
            ],

            // Zipper Products
            [
                'cat_id' => $zipperCategory->id,
                'type_id' => $accessoriesType->id,
                'product_name' => 'Zipper 12 Inch',
                'unit_type' => 'PCS',
                'opening_qty' => 200,
                'current_qty' => 200,
                'status' => 1,
            ],
            [
                'cat_id' => $zipperCategory->id,
                'type_id' => $accessoriesType->id,
                'product_name' => 'Zipper 18 Inch',
                'unit_type' => 'PCS',
                'opening_qty' => 150,
                'current_qty' => 150,
                'status' => 1,
            ],
            [
                'cat_id' => $zipperCategory->id,
                'type_id' => $accessoriesType->id,
                'product_name' => 'Zipper 24 Inch',
                'unit_type' => 'PCS',
                'opening_qty' => 100,
                'current_qty' => 100,
                'status' => 1,
            ],

            // Button Products
            [
                'cat_id' => $buttonCategory->id,
                'type_id' => $accessoriesType->id,
                'product_name' => 'Plastic Button Small',
                'unit_type' => 'PCS',
                'opening_qty' => 500,
                'current_qty' => 500,
                'status' => 1,
            ],
            [
                'cat_id' => $buttonCategory->id,
                'type_id' => $accessoriesType->id,
                'product_name' => 'Plastic Button Large',
                'unit_type' => 'PCS',
                'opening_qty' => 300,
                'current_qty' => 300,
                'status' => 1,
            ],

            // Foam Products
            [
                'cat_id' => $foamCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Memory Foam',
                'unit_type' => 'KG',
                'opening_qty' => 400,
                'current_qty' => 400,
                'status' => 1,
            ],
            [
                'cat_id' => $foamCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Polyurethane Foam',
                'unit_type' => 'KG',
                'opening_qty' => 350,
                'current_qty' => 350,
                'status' => 1,
            ],

            // Cotton Products
            [
                'cat_id' => $cottonCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Cotton Balls',
                'unit_type' => 'KG',
                'opening_qty' => 600,
                'current_qty' => 600,
                'status' => 1,
            ],
            [
                'cat_id' => $cottonCategory->id,
                'type_id' => $rawMaterialType->id,
                'product_name' => 'Cotton Batting',
                'unit_type' => 'KG',
                'opening_qty' => 450,
                'current_qty' => 450,
                'status' => 1,
            ],

            // Pillow Cover Products
            [
                'cat_id' => $pillowCoverCategory->id,
                'type_id' => $finishedProductType->id,
                'product_name' => 'Pillow Cover Standard',
                'unit_type' => 'PCS',
                'opening_qty' => 200,
                'current_qty' => 200,
                'status' => 1,
            ],
            [
                'cat_id' => $pillowCoverCategory->id,
                'type_id' => $finishedProductType->id,
                'product_name' => 'Pillow Cover Premium',
                'unit_type' => 'PCS',
                'opening_qty' => 150,
                'current_qty' => 150,
                'status' => 1,
            ],

            // Quilt Cover Products
            [
                'cat_id' => $quiltCoverCategory->id,
                'type_id' => $finishedProductType->id,
                'product_name' => 'Quilt Cover Single',
                'unit_type' => 'PCS',
                'opening_qty' => 100,
                'current_qty' => 100,
                'status' => 1,
            ],
            [
                'cat_id' => $quiltCoverCategory->id,
                'type_id' => $finishedProductType->id,
                'product_name' => 'Quilt Cover Double',
                'unit_type' => 'PCS',
                'opening_qty' => 80,
                'current_qty' => 80,
                'status' => 1,
            ],
            [
                'cat_id' => $quiltCoverCategory->id,
                'type_id' => $finishedProductType->id,
                'product_name' => 'Quilt Cover King Size',
                'unit_type' => 'PCS',
                'opening_qty' => 60,
                'current_qty' => 60,
                'status' => 1,
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'cat_id' => $product['cat_id'],
                'type_id' => $product['type_id'],
                'product_name' => $product['product_name'],
                'unit_type' => $product['unit_type'],
                'opening_qty' => $product['opening_qty'],
                'current_qty' => $product['current_qty'],
                'status' => $product['status'],
                'is_deleted' => false,
            ]);
        }
    }
}
