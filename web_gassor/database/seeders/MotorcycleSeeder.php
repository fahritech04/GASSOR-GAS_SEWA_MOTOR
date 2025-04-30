<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'Scoopy 125cc',
                'motorcycle_type' => 'matic',
                'square_feet' => 250,
                'capacity' => 2,
                'price_per_day' => 50000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 1,
                'name' => 'Scoopy 50cc',
                'motorcycle_type' => 'matic',
                'square_feet' => 400,
                'capacity' => 4,
                'price_per_day' => 45000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 1,
                'name' => 'Scoopy 200cc',
                'motorcycle_type' => 'sport',
                'square_feet' => 400,
                'capacity' => 4,
                'price_per_day' => 60000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'name' => 'Vario 125cc',
                'motorcycle_type' => 'matic',
                'square_feet' => 250,
                'capacity' => 2,
                'price_per_day' => 45000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'name' => 'Vario 50cc',
                'motorcycle_type' => 'matic',
                'square_feet' => 400,
                'capacity' => 4,
                'price_per_day' => 55000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'name' => 'Vario 200cc',
                'motorcycle_type' => 'sport',
                'square_feet' => 400,
                'capacity' => 4,
                'price_per_day' => 65000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'name' => 'Beat 125cc',
                'motorcycle_type' => 'matic',
                'square_feet' => 250,
                'capacity' => 2,
                'price_per_day' => 50000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'name' => 'Beat 50cc',
                'motorcycle_type' => 'matic',
                'square_feet' => 400,
                'capacity' => 4,
                'price_per_day' => 45000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'name' => 'Beat 200cc',
                'motorcycle_type' => 'sport',
                'square_feet' => 400,
                'capacity' => 4,
                'price_per_day' => 60000,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('motorcycles')->insert($motorcycles);
    }
}
