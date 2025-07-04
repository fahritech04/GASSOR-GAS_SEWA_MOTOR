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
                'name' => 'The Master Rental',
                'slug' => Str::slug('the-master-rental'),
                'thumbnail' => 'motorbike_rental/themaster_rental.jpg',
                'city_id' => 1,
                'description' => 'The Master Rental adalah layanan penyewaan motor terpercaya yang berlokasi di kawasan Jl. Sukabirus. Tersedia berbagai pilihan motor matic yang irit bahan bakar dan cocok untuk mobilitas harian di kota.',
                'address' => 'Jl. Sukabirus',
                'contact' => '6282149820129',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Semangat Jaya Motor Rental',
                'slug' => Str::slug('semangat-jaya-motor-rental'),
                'thumbnail' => 'motorbike_rental/semangatjayamotor_rental.jpg',
                'city_id' => 2,
                'description' => 'Semangat Jaya Motor Rental hadir sebagai sahabat terbaik para perantau yang membutuhkan kendaraan andal untuk menjelajahi kota baru. Berlokasi di Jl. Sukapura, rental ini menyediakan unit-unit motor modern seperti Honda Vario yang cocok untuk perjalanan jauh maupun kebutuhan harian. Dengan pelayanan yang ramah dan harga terjangkau, Semangat Jaya Motor menjadi pilihan utama para penjelajah kota.',
                'address' => 'Jl. Sukapura',
                'contact' => '6282149820129',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rental Baik',
                'slug' => Str::slug('rental-baik'),
                'thumbnail' => 'motorbike_rental/rentalbaik_rental.jpg',
                'city_id' => 3,
                'description' => 'Rental baik merupakan penyedia jasa sewa motor yang mengedepankan kemudahan dan keberkahan dalam setiap perjalanan. Terletak di Jl. Bojongsoang, tempat ini menawarkan beragam motor matic seperti Honda Beat yang efisien dan praktis. Dengan harga bersahabat dan proses sewa yang cepat, Berkah Motor menjadi solusi transportasi yang berkah dan bermanfaat.',
                'address' => 'Jl. Bojongsoang',
                'contact' => '6282149820129',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('motorbike_rentals')->insert($rentals);
    }
}
