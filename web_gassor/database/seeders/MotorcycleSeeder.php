<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotorcycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $motorcycles = [
            [
                'motorbike_rental_id' => 1,
                'owner_id' => 1,
                'name' => 'Scoopy 125cc',
                'motorcycle_type' => 'matic',
                'vehicle_number_plate' => 'B 1234 KZT',
                'stnk' => 'Tersedia',
                'price_per_day' => 50000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 1,
                'owner_id' => 1,
                'name' => 'Scoopy 50cc',
                'motorcycle_type' => 'matic',
                'vehicle_number_plate' => 'D 6789 MUI',
                'stnk' => 'Tersedia',
                'price_per_day' => 45000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 1,
                'owner_id' => 1,
                'name' => 'Scoopy 200cc',
                'motorcycle_type' => 'sport',
                'vehicle_number_plate' => 'L 4321 ZPA',
                'stnk' => 'Tersedia',
                'price_per_day' => 60000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'owner_id' => 2,
                'name' => 'Vario 125cc',
                'motorcycle_type' => 'matic',
                'vehicle_number_plate' => 'AB 9090 TKX',
                'stnk' => 'Tersedia',
                'price_per_day' => 45000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'owner_id' => 2,
                'name' => 'Vario 50cc',
                'motorcycle_type' => 'matic',
                'vehicle_number_plate' => 'F 1122 VGB',
                'stnk' => 'Tersedia',
                'price_per_day' => 55000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'owner_id' => 2,
                'name' => 'Vario 200cc',
                'motorcycle_type' => 'sport',
                'vehicle_number_plate' => 'H 3344 LWN',
                'stnk' => 'Tersedia',
                'price_per_day' => 65000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'owner_id' => 3,
                'name' => 'Beat 125cc',
                'motorcycle_type' => 'matic',
                'vehicle_number_plate' => 'DA 5566 QRP',
                'stnk' => 'Tersedia',
                'price_per_day' => 50000,
                'is_available' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'owner_id' => 3,
                'name' => 'Beat 50cc',
                'motorcycle_type' => 'matic',
                'vehicle_number_plate' => 'BK 7788 YUE',
                'stnk' => 'Tersedia',
                'price_per_day' => 45000,
                'is_available' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'owner_id' => 3,
                'name' => 'Beat 200cc',
                'motorcycle_type' => 'sport',
                'vehicle_number_plate' => 'KT 9900 HDS',
                'stnk' => 'Tersedia',
                'price_per_day' => 60000,
                'is_available' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('motorcycles')->insert($motorcycles);
    }
}
