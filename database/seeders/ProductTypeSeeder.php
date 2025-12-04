<?php

namespace Database\Seeders;

use App\Models\ProductType;
use App\Models\Company;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Zareena Industries company
        $company = Company::where('name', 'Zareena Industries')->first();
        
        if (!$company) {
            $this->command->error('Company not found. Please run CompanySeeder first.');
            return;
        }

        $types = [
            ['name' => 'Raw Material', 'status' => 1],
            ['name' => 'Finished Product', 'status' => 1],
            ['name' => 'Accessories', 'status' => 1],
            ['name' => 'Packaging Material', 'status' => 1],
            ['name' => 'Semi-Finished', 'status' => 1],
        ];

        foreach ($types as $type) {
            ProductType::create([
                'name' => $type['name'],
                'status' => $type['status'],
                'is_deleted' => false,
                'company_id' => $company->id,
            ]);
        }
    }
}
