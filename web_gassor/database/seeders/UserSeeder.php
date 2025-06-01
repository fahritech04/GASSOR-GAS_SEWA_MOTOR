<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('users')->insert([
        //     'name' => 'pemilik',
        //     'email' => 'pemilik@gmail.com',
        //     'password' => Hash::make('pemilik'),
        //     'role' => "pemilik",

        // ]);

        $owners = [];
        for ($i = 1; $i <= 10; $i++) {
            $owners[] = [
                'name' => 'Pemilik ' . $i,
                'username' => 'pemilik' . $i,
                'email' => 'pemilik' . $i . '@gmail.com',
                'password' => Hash::make('pemilik' . $i),
                'role' => 'pemilik',
                'profile_image_url' => null,
                'tempat_lahir' => 'Kota ' . $i,
                'tanggal_lahir' => '1990-01-0' . $i,
                'phone' => '0812345678' . $i,
            ];
        }

        DB::table('users')->insert($owners);
    }
}
