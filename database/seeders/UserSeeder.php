<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@zareenaindustries.com',
            'password' => Hash::make('12345678'),
            'role' => 1, // 1 = Admin, 2 = Manager
            'status' => 1, // Active
            'is_deleted' => false,
        ]);

        // Create a manager user
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@zareenaindustries.com',
            'password' => Hash::make('12345678'),
            'role' => 2, // 1 = Admin, 2 = Manager
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

