<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bonuses = [
            [
                'motorbike_rental_id' => 1,
                'image' => 'bonuses/helm.jpg',
                'name' => 'Helm',
                'description' => '1 Helm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 1,
                'image' => 'bonuses/jas-hujan.jpg',
                'name' => 'Jas Hujan',
                'description' => '1 Jas Hujan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 2,
                'image' => 'bonuses/helm.jpg',
                'name' => 'Helm',
                'description' => '1 Helm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'motorbike_rental_id' => 3,
                'image' => 'bonuses/helm.jpg',
                'name' => 'Helm',
                'description' => '1 Helm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('bonuses')->insert($bonuses);
    }
}
