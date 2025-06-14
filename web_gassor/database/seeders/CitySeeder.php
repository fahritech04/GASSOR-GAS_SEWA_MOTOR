<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'image' => 'cities/city-icon.jpg',
                'name' => 'Bojongsoang',
                'slug' => 'bojongsoang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'cities/city-icon.jpg',
                'name' => 'Sukapura',
                'slug' => 'sukapura',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'cities/city-icon.jpg',
                'name' => 'Sukabirus',
                'slug' => 'sukabirus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('cities')->insert($cities);
    }
}
