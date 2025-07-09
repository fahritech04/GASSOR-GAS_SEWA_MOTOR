<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owners = [];
        for ($i = 1; $i <= 3; $i++) {
            $owners[] = [
                'name' => 'Pemilik '.$i,
                'username' => 'pemilik'.$i,
                'email' => 'pemilik'.$i.'@gmail.com',
                'password' => Hash::make('pemilik'.$i),
                'role' => 'pemilik',
                'profile_image_url' => null,
                'tempat_lahir' => 'Kota '.$i,
                'tanggal_lahir' => '2004-01-0'.$i,
                'phone' => '6285174309823'.$i,
            ];
        }

        DB::table('users')->insert($owners);

        $penyewa = [];
        for ($i = 1; $i <= 3; $i++) {
            $penyewa[] = [
                'name' => 'Penyewa '.$i,
                'username' => 'penyewa'.$i,
                'email' => 'penyewa'.$i.'@gmail.com',
                'password' => Hash::make('penyewa'.$i),
                'role' => 'penyewa',
                'profile_image_url' => null,
                'tempat_lahir' => 'Kota Penyewa '.$i,
                'tanggal_lahir' => '1995-01-0'.$i,
                'phone' => '6285174309800'.$i,
            ];
        }

        DB::table('users')->insert($penyewa);
    }
}
