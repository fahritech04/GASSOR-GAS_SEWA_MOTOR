<?php

namespace Database\Seeders;

use App\Models\Motorcycle;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get sample users and motorcycles
        $users = User::where('role', 'penyewa')->take(3)->get();
        $motorcycles = Motorcycle::take(5)->get();

        if ($users->count() > 0 && $motorcycles->count() > 0) {
            $transactionData = [
                [
                    'code' => 'TRX'.str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                    'motorcycle_id' => $motorcycles->first()->id,
                    'motorbike_rental_id' => $motorcycles->first()->motorbike_rental_id,
                    'name' => $users->first()->name,
                    'email' => $users->first()->email,
                    'phone_number' => '081234567890',
                    'payment_method' => 'full_payment',
                    'payment_status' => 'success',
                    'rental_status' => 'finished',
                    'start_date' => Carbon::now()->subDays(10),
                    'start_time' => '08:00:00',
                    'end_time' => '18:00:00',
                    'duration' => 3,
                    'total_amount' => $motorcycles->first()->price_per_day * 3,
                    'transaction_date' => Carbon::now()->subDays(10),
                    'created_at' => Carbon::now()->subDays(10),
                    'updated_at' => Carbon::now()->subDays(7),
                ],
                [
                    'code' => 'TRX'.str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                    'motorcycle_id' => $motorcycles->skip(1)->first()->id ?? $motorcycles->first()->id,
                    'motorbike_rental_id' => $motorcycles->skip(1)->first()->motorbike_rental_id ?? $motorcycles->first()->motorbike_rental_id,
                    'name' => $users->skip(1)->first()->name ?? $users->first()->name,
                    'email' => $users->skip(1)->first()->email ?? $users->first()->email,
                    'phone_number' => '081234567891',
                    'payment_method' => 'full_payment',
                    'payment_status' => 'success',
                    'rental_status' => 'finished',
                    'start_date' => Carbon::now()->subDays(15),
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'duration' => 2,
                    'total_amount' => ($motorcycles->skip(1)->first()->price_per_day ?? $motorcycles->first()->price_per_day) * 2,
                    'transaction_date' => Carbon::now()->subDays(15),
                    'created_at' => Carbon::now()->subDays(15),
                    'updated_at' => Carbon::now()->subDays(13),
                ],
                [
                    'code' => 'TRX'.str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT),
                    'motorcycle_id' => $motorcycles->skip(2)->first()->id ?? $motorcycles->first()->id,
                    'motorbike_rental_id' => $motorcycles->skip(2)->first()->motorbike_rental_id ?? $motorcycles->first()->motorbike_rental_id,
                    'name' => $users->last()->name,
                    'email' => $users->last()->email,
                    'phone_number' => '081234567892',
                    'payment_method' => 'full_payment',
                    'payment_status' => 'success',
                    'rental_status' => 'finished',
                    'start_date' => Carbon::now()->subDays(5),
                    'start_time' => '10:00:00',
                    'end_time' => '16:00:00',
                    'duration' => 1,
                    'total_amount' => $motorcycles->skip(2)->first()->price_per_day ?? $motorcycles->first()->price_per_day,
                    'transaction_date' => Carbon::now()->subDays(5),
                    'created_at' => Carbon::now()->subDays(5),
                    'updated_at' => Carbon::now()->subDays(4),
                ],
            ];

            foreach ($transactionData as $data) {
                Transaction::create($data);
            }
        }
    }
}
