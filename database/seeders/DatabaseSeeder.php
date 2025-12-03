<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders in order (respecting dependencies)
        $this->call([
            UserSeeder::class,
            ProductCategorySeeder::class,
            ProductTypeSeeder::class,
            ProductSeeder::class,
            VendorSeeder::class,
            WarehouseSeeder::class,
            SuperAdminSeeder::class,
        ]);
    }
}
