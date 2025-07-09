<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MotorcycleReview;
use App\Models\Transaction;
use App\Models\User;

class MotorcycleReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = Transaction::where('rental_status', 'finished')
                                  ->where('payment_status', 'success')
                                  ->get();

        foreach ($transactions->take(2) as $transaction) {
            // Find user by email
            $user = User::where('email', $transaction->email)->first();

            if ($user) {
                MotorcycleReview::create([
                    'motorcycle_id' => $transaction->motorcycle_id,
                    'transaction_id' => $transaction->id,
                    'user_id' => $user->id,
                    'rating' => rand(4, 5),
                    'comment' => $this->getRandomComment(),
                ]);
            }
        }
    }

    private function getRandomComment()
    {
        $comments = [
            'Motor sangat bagus dan bersih. Pelayanan memuaskan!',
            'Sewa motor di sini sangat recommended. Harga terjangkau dan motornya terawat.',
            'Pengalaman menyewa motor yang menyenangkan. Akan kembali lagi!',
            'Motor dalam kondisi prima, pemilik juga ramah dan responsif.',
            'Layanan cepat dan motor sesuai ekspektasi. Terima kasih!',
        ];

        return $comments[array_rand($comments)];
    }
}
