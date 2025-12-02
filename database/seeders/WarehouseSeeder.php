<?php

namespace Database\Seeders;

use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [
            [
                'name' => 'Main Warehouse',
                'code' => 'WH-001',
                'contact' => '+92-300-1234567',
                'address' => 'D-27 Manghopir Road, S.I.T.E. Karachi.',
                'email' => 'mainwarehouse@zareenaindustries.com',
                'status' => 1,
            ],
            [
                'name' => 'Storage Unit A',
                'code' => 'WH-002',
                'contact' => '+92-321-2345678',
                'address' => 'Block 5, Industrial Area, Karachi.',
                'email' => 'storagea@zareenaindustries.com',
                'status' => 1,
            ],
            [
                'name' => 'Storage Unit B',
                'code' => 'WH-003',
                'contact' => '+92-300-3456789',
                'address' => 'Sector 7, S.I.T.E Area, Karachi.',
                'email' => 'storageb@zareenaindustries.com',
                'status' => 1,
            ],
            [
                'name' => 'Finished Goods Warehouse',
                'code' => 'WH-004',
                'contact' => '+92-321-4567890',
                'address' => 'Plot #12, Textile Zone, Karachi.',
                'email' => 'finishedgoods@zareenaindustries.com',
                'status' => 1,
            ],
            [
                'name' => 'Raw Material Storage',
                'code' => 'WH-005',
                'contact' => '+92-300-5678901',
                'address' => 'Warehouse Complex, Port Area, Karachi.',
                'email' => 'rawmaterial@zareenaindustries.com',
                'status' => 1,
            ],
        ];

        foreach ($warehouses as $warehouse) {
            Warehouse::create([
                'name' => $warehouse['name'],
                'code' => $warehouse['code'],
                'contact' => $warehouse['contact'],
                'address' => $warehouse['address'],
                'email' => $warehouse['email'],
                'status' => $warehouse['status'],
                'is_deleted' => false,
            ]);
        }
    }
}
