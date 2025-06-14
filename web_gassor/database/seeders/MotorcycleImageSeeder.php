<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotorcycleImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $motorcycleImages = [
            [
                'motorcycle_id' => 1,
                'image' => 'motorcycles/scoopy-1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorcycle_id' => 2,
                'image' => 'motorcycles/scoopy-2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorcycle_id' => 3,
                'image' => 'motorcycles/scoopy-3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorcycle_id' => 4,
                'image' => 'motorcycles/vario-1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorcycle_id' => 5,
                'image' => 'motorcycles/vario-2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorcycle_id' => 6,
                'image' => 'motorcycles/vario-3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorcycle_id' => 7,
                'image' => 'motorcycles/vario-1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorcycle_id' => 8,
                'image' => 'motorcycles/vario-2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorcycle_id' => 9,
                'image' => 'motorcycles/vario-3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('motorcycle_images')->insert($motorcycleImages);
    }
}
