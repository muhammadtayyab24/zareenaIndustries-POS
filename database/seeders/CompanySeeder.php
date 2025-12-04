<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Zareena Industries company
        $company = Company::firstOrCreate(
            ['email' => 'info@zareenaindustries.com'],
            [
                'name' => 'Zareena Industries',
                'address' => 'D-27 Manghopir Road, S.I.T.E. Karachi.',
                'email' => 'info@zareenaindustries.com',
                'ntn' => '3238408-4',
                'strn' => '17-00-3238-408-14',
                'tel_no' => '92-021-32588033',
                'mobile_no' => null,
                'website' => null,
                'logo' => null,
                'favicon' => null,
                'status' => 1,
                'is_deleted' => false,
            ]
        );

        $this->command->info('Company seeded successfully!');
        $this->command->info('Company: ' . $company->name);
        $this->command->info('Company ID: ' . $company->id);
    }
}

