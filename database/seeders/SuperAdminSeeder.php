<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        $existingSuperAdmin = User::where('role', 0)
            ->whereNull('company_id')
            ->first();

        if ($existingSuperAdmin) {
            $this->command->info('Super Admin already exists. Skipping...');
            return;
        }

        // Create Super Admin (only one)
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@system.com',
            'password' => Hash::make('12345678'),
            'role' => 0, // 0 = Super Admin
            'company_id' => null, // Super admin has no company
            'status' => 1, // Active
            'is_deleted' => false,
        ]);

        $this->command->info('Super Admin created successfully!');
        $this->command->info('Email: superadmin@system.com');
        $this->command->info('Password: 12345678');
    }
}
