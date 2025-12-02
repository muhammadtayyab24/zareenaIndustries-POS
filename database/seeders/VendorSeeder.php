<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'SAAZ GOLD',
                'type' => 'Credit',
                'contact' => '+92-300-1234567',
                'ntn' => '1483639-4',
                'address' => 'SUITE #12, 5TH FLOOR CENTRALPLAZA BARKAT MARKET, NEW GARDEN TOWN LAHORE.',
                'email' => 'saazgold@example.com',
                'status' => 1,
            ],
            [
                'name' => 'Textile Solutions Pvt Ltd',
                'type' => 'Credit',
                'contact' => '+92-321-9876543',
                'ntn' => '1234567-8',
                'address' => 'Plot #45, Industrial Area, Karachi.',
                'email' => 'textilesolutions@example.com',
                'status' => 1,
            ],
            [
                'name' => 'Polyester Industries',
                'type' => 'Cash',
                'contact' => '+92-300-5551234',
                'ntn' => '2345678-9',
                'address' => 'Block A, S.I.T.E Area, Karachi.',
                'email' => 'polyester@example.com',
                'status' => 1,
            ],
            [
                'name' => 'Fabric World',
                'type' => 'Credit',
                'contact' => '+92-321-4445678',
                'ntn' => '3456789-0',
                'address' => 'Main Boulevard, Gulberg, Lahore.',
                'email' => 'fabricworld@example.com',
                'status' => 1,
            ],
            [
                'name' => 'Home Textile Suppliers',
                'type' => 'Credit',
                'contact' => '+92-300-7778901',
                'ntn' => '4567890-1',
                'address' => 'Commercial Street, Faisalabad.',
                'email' => 'hometextile@example.com',
                'status' => 1,
            ],
            [
                'name' => 'Cotton Mills Limited',
                'type' => 'Cash',
                'contact' => '+92-321-6662345',
                'ntn' => '5678901-2',
                'address' => 'Textile Zone, Multan.',
                'email' => 'cottonmills@example.com',
                'status' => 1,
            ],
            [
                'name' => 'Accessories Hub',
                'type' => 'Credit',
                'contact' => '+92-300-8883456',
                'ntn' => '6789012-3',
                'address' => 'Market Road, Rawalpindi.',
                'email' => 'accessorieshub@example.com',
                'status' => 1,
            ],
            [
                'name' => 'Premium Polyfill Co.',
                'type' => 'Credit',
                'contact' => '+92-321-9994567',
                'ntn' => '7890123-4',
                'address' => 'Industrial Estate, Sialkot.',
                'email' => 'premiumpolyfill@example.com',
                'status' => 1,
            ],
            [
                'name' => 'Thread & Zipper Mart',
                'type' => 'Cash',
                'contact' => '+92-300-1115678',
                'ntn' => '8901234-5',
                'address' => 'Trade Center, Gujranwala.',
                'email' => 'threadzipper@example.com',
                'status' => 1,
            ],
            [
                'name' => 'Foam & Cotton Suppliers',
                'type' => 'Credit',
                'contact' => '+92-321-2226789',
                'ntn' => '9012345-6',
                'address' => 'Business District, Islamabad.',
                'email' => 'foamcotton@example.com',
                'status' => 1,
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::create([
                'name' => $vendor['name'],
                'type' => $vendor['type'],
                'contact' => $vendor['contact'],
                'ntn' => $vendor['ntn'],
                'address' => $vendor['address'],
                'email' => $vendor['email'],
                'status' => $vendor['status'],
                'is_deleted' => false,
            ]);
        }
    }
}
