<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MotorbikeRentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rentals = [
            [
                'name' => 'Rental Restu Ibu',
                'slug' => Str::slug('rehtal-restu-ibu'),
                'thumbnail' => 'motorbike_rental/scoopy.jpg',
                'city_id' => 1,
                'category_id' => 1,
                'description' => 'Rental Restu Ibu adalah layanan penyewaan motor terpercaya yang berlokasi di kawasan Jl. Sukabirus. Mengusung nama yang identik dengan kehangatan dan kepercayaan, rental ini berkomitmen memberikan kenyamanan dan rasa aman layaknya restu dari seorang ibu. Tersedia berbagai pilihan motor matic yang irit bahan bakar dan cocok untuk mobilitas harian di kota.',
                'address' => 'Jl. Sukabirus',
                'contact' => '6282149820129',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Perantau Sejati',
                'slug' => Str::slug('perantau-sejati'),
                'thumbnail' => 'motorbike_rental/vario.jpg',
                'city_id' => 2,
                'category_id' => 2,
                'description' => 'Perantau Sejati hadir sebagai sahabat terbaik para perantau yang membutuhkan kendaraan andal untuk menjelajahi kota baru. Berlokasi di Jl. Sukapura, rental ini menyediakan unit-unit motor modern seperti Honda Vario yang cocok untuk perjalanan jauh maupun kebutuhan harian. Dengan pelayanan yang ramah dan harga terjangkau, Perantau Sejati menjadi pilihan utama para penjelajah kota.',
                'address' => 'Jl. Sukapura',
                'contact' => '6285174309823',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Berkah Motor',
                'slug' => Str::slug('berkah-motor'),
                'thumbnail' => 'motorbike_rental/beat.jpg',
                'city_id' => 3,
                'category_id' => 3,
                'description' => 'Berkah Motor merupakan penyedia jasa sewa motor yang mengedepankan kemudahan dan keberkahan dalam setiap perjalanan. Terletak di Jl. Bojongsoang, tempat ini menawarkan beragam motor matic seperti Honda Beat yang efisien dan praktis. Dengan harga bersahabat dan proses sewa yang cepat, Berkah Motor menjadi solusi transportasi yang berkah dan bermanfaat.',
                'address' => 'Jl. Bojongsoang',
                'contact' => '6281348172439',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('motorbike_rentals')->insert($rentals);
    }
}
