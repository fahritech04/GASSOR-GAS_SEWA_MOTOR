<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'Honda Scoopy',
                'slug' => Str::slug('honda scoopy'),
                'thumbnail' => 'motorbike_rental/scoopy.jpg',
                'city_id' => 1,
                'category_id' => 1,
                'description' => 'Honda Scoopy adalah motor matic dengan mesin 4-langkah, SOHC, eSP, dan kapasitas 109,5 cc. Motor ini memiliki transmisi otomatis CVT. ',
                'address' => 'Jl. Sukabirus',
                'contact' => '6281234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Honda Vario',
                'slug' => Str::slug('honda vario'),
                'thumbnail' => 'motorbike_rental/vario.jpg',
                'city_id' => 2,
                'category_id' => 2,
                'description' => 'Honda Vario (juga dikenal sebagai Honda Click di beberapa negara Asia Tenggara) adalah sebuah skuter bertransmisi otomatis yang diproduksi oleh Astra Honda Motor di Indonesia sejak tahun 2006. Skuter ini dimaksudkan untuk mengantisipasi makin banyaknya populasi skuter otomatis yang beredar di pasar sepeda motor Indonesia.[2][3] Vario telah muncul dalam berbagai varian dengan kapasitas mesin mulai dari 108,0 cc (6,6 cu in) sampai 157,0 cc (9,6 cu in).',
                'address' => 'Jl. Sukapura',
                'contact' => '6289876543210',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Honda Beat',
                'slug' => Str::slug('honda beat'),
                'thumbnail' => 'motorbike_rental/beat.jpg',
                'city_id' => 3,
                'category_id' => 3,
                'description' => 'Honda Beat adalah sepeda motor matic yang diproduksi oleh Honda. Motor ini memiliki mesin 109.5 cc atau 110 cc, dengan transmisi otomatis. Honda Beat memiliki berbagai fitur, seperti eSAF, Combi Brake System (CBS), dan ISS.',
                'address' => 'Jl. Bojongsoang',
                'contact' => '6281122334455',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('motorbike_rentals')->insert($rentals);
    }
}
