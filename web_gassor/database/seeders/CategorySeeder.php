<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'image' => 'categories/matic.jpg',
                'name' => 'Matic',
                'slug' => Str::slug('matic'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'categories/sport.jpg',
                'name' => 'Sport',
                'slug' => Str::slug('sport'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'categories/cub.jpg',
                'name' => 'Cub',
                'slug' => Str::slug('cub'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'categories/offroad.jpg',
                'name' => 'Offroad',
                'slug' => Str::slug('offroad'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'categories/chopper.jpg',
                'name' => 'Chopper',
                'slug' => Str::slug('chopper'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'categories/scooter.jpg',
                'name' => 'Scooter',
                'slug' => Str::slug('scooter'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
