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
                'category_id' => 1,
                'name' => 'Scoopy 125cc',
                'vehicle_number_plate' => 'B 1234 KZT',
                'stnk' => 'Tersedia',
                'stnk_images' => json_encode(['stnk_images/stnk_depan.png', 'stnk_images/stnk_belakang.png']),
                'price_per_day' => 50000,
                'stock' => 1,
                'available_stock' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 1,
                'owner_id' => 1,
                'category_id' => 1,
                'name' => 'Scoopy 50cc',
                'vehicle_number_plate' => 'D 6789 MUI',
                'stnk' => 'Tersedia',
                'stnk_images' => json_encode(['stnk_images/stnk_depan.png', 'stnk_images/stnk_belakang.png']),
                'price_per_day' => 45000,
                'stock' => 1,
                'available_stock' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 1,
                'owner_id' => 1,
                'category_id' => 2,
                'name' => 'Scoopy 200cc',
                'vehicle_number_plate' => 'L 4321 ZPA',
                'stnk' => 'Tersedia',
                'stnk_images' => json_encode(['stnk_images/stnk_depan.png', 'stnk_images/stnk_belakang.png']),
                'price_per_day' => 60000,
                'stock' => 1,
                'available_stock' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'owner_id' => 2,
                'category_id' => 1,
                'name' => 'Vario 125cc',
                'vehicle_number_plate' => 'AB 9090 TKX',
                'stnk' => 'Tersedia',
                'stnk_images' => json_encode(['stnk_images/stnk_depan.png', 'stnk_images/stnk_belakang.png']),
                'price_per_day' => 45000,
                'stock' => 1,
                'available_stock' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'owner_id' => 2,
                'category_id' => 1,
                'name' => 'Vario 50cc',
                'vehicle_number_plate' => 'F 1122 VGB',
                'stnk' => 'Tersedia',
                'stnk_images' => json_encode(['stnk_images/stnk_depan.png', 'stnk_images/stnk_belakang.png']),
                'price_per_day' => 55000,
                'stock' => 1,
                'available_stock' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'owner_id' => 2,
                'category_id' => 2,
                'name' => 'Vario 200cc',
                'vehicle_number_plate' => 'H 3344 LWN',
                'stnk' => 'Tersedia',
                'stnk_images' => json_encode(['stnk_images/stnk_depan.png', 'stnk_images/stnk_belakang.png']),
                'price_per_day' => 65000,
                'stock' => 1,
                'available_stock' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'owner_id' => 3,
                'category_id' => 1,
                'name' => 'Beat 125cc',
                'vehicle_number_plate' => 'DA 5566 QRP',
                'stnk' => 'Tersedia',
                'stnk_images' => json_encode(['stnk_images/stnk_depan.png', 'stnk_images/stnk_belakang.png']),
                'price_per_day' => 50000,
                'stock' => 1,
                'available_stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'owner_id' => 3,
                'category_id' => 1,
                'name' => 'Beat 50cc',
                'vehicle_number_plate' => 'BK 7788 YUE',
                'stnk' => 'Tersedia',
                'stnk_images' => json_encode(['stnk_images/stnk_depan.png', 'stnk_images/stnk_belakang.png']),
                'price_per_day' => 45000,
                'stock' => 1,
                'available_stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'owner_id' => 3,
                'category_id' => 2,
                'name' => 'Beat 200cc',
                'vehicle_number_plate' => 'KT 9900 HDS',
                'stnk' => 'Tersedia',
                'stnk_images' => json_encode(['stnk_images/stnk_depan.png', 'stnk_images/stnk_belakang.png']),
                'price_per_day' => 60000,
                'stock' => 1,
                'available_stock' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('motorcycles')->insert($motorcycles);
    }
}
