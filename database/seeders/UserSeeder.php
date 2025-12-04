<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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

        // Create default admin user (Company Admin)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@zareenaindustries.com',
            'password' => Hash::make('12345678'),
            'role' => 1, // 1 = Company Admin
            'company_id' => $company->id,
            'status' => 1, // Active
            'is_deleted' => false,
        ]);

        // Create a manager user (Company Manager)
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@zareenaindustries.com',
            'password' => Hash::make('12345678'),
            'role' => 2, // 2 = Company Manager
            'company_id' => $company->id,
            'status' => 1, // Active
            'is_deleted' => false,
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin Email: admin@zareenaindustries.com');
        $this->command->info('Admin Password: 12345678');
        $this->command->info('Manager Email: manager@zareenaindustries.com');
        $this->command->info('Manager Password: 12345678');
    }
}

