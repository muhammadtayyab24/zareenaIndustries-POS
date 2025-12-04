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
            CompanySeeder::class,        // Must be first - creates Zareena Industries
            SuperAdminSeeder::class,      // Create Super Admin (no company_id)
            UserSeeder::class,            // Create Company Admin and Manager (with company_id)
            ProductCategorySeeder::class,
            ProductTypeSeeder::class,
            ProductSeeder::class,
            VendorSeeder::class,
            WarehouseSeeder::class,
        ]);
    }
}
