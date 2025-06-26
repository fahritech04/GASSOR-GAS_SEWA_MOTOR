<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

class MidtransStatusSyncService
{
    /**
     * Sinkronisasi status pembayaran dengan Midtrans
     */
    public function syncPaymentStatus(Transaction $transaction)
    {
        try {
            // Setup Midtrans config
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            \Midtrans\Config::$isProduction = config('midtrans.isProduction');

            // Ambil status terbaru dari Midtrans
            $status = \Midtrans\Transaction::status($transaction->code);
            $midtransStatus = strtolower($status->transaction_status ?? '');

            Log::info('Sinkronkan status pembayaran dari Midtrans', [
                'order_id' => $transaction->code,
                'old_status' => $transaction->payment_status,
                'midtrans_status' => $midtransStatus
            ]);

            // Map status Midtrans ke status internal
            $oldPaymentStatus = $transaction->payment_status;
            $newPaymentStatus = $this->mapMidtransStatus($midtransStatus);

            // Update jika ada perubahan
            if ($oldPaymentStatus !== $newPaymentStatus) {
                $transaction->update(['payment_status' => $newPaymentStatus]);

                // Handle perubahan status motor jika pembayaran berhasil
                if ($newPaymentStatus === 'success' && $oldPaymentStatus !== 'success') {
                    $this->handleSuccessfulPayment($transaction);
                }

                Log::info('Status pembayaran diperbarui', [
                    'order_id' => $transaction->code,
                    'from' => $oldPaymentStatus,
                    'to' => $newPaymentStatus
                ]);
            }

            return $transaction->fresh();

        } catch (\Exception $e) {
            Log::error('Gagal menyinkronkan status pembayaran dari Midtrans', [
                'order_id' => $transaction->code,
                'error' => $e->getMessage()
            ]);

            return $transaction;
        }
    }

    /**
     * Map status dari Midtrans ke status internal
     */
    private function mapMidtransStatus($midtransStatus)
    {
        switch ($midtransStatus) {
            case 'settlement':
            case 'capture':
                return 'success';
            case 'pending':
                return 'pending';
            case 'deny':
                return 'failed';
            case 'expire':
                return 'expired';
            case 'cancel':
                return 'canceled';
            default:
                return 'unknown';
        }
    }

    /**
     * Handle pembayaran yang berhasil
     */
    private function handleSuccessfulPayment(Transaction $transaction)
    {
        if ($transaction->motorcycle && $transaction->motorcycle->status !== 'on_going') {
            $motorcycle = $transaction->motorcycle;
            $motorcycle->decreaseStock(1);
            $motorcycle->update(['status' => 'on_going']);

            Log::info('Status motor diperbarui menjadi on_going', [
                'motorcycle_id' => $motorcycle->id,
                'transaction_code' => $transaction->code
            ]);
        }
    }

    /**
     * Sinkronisasi bulk untuk transaksi yang pending
     */
    public function syncPendingTransactions()
    {
        $pendingTransactions = Transaction::where('payment_status', 'pending')
            ->where('created_at', '>=', now()->subDays(7)) // hanya 7 hari terakhir
            ->get();

        $synced = 0;
        foreach ($pendingTransactions as $transaction) {
            $updated = $this->syncPaymentStatus($transaction);
            if ($updated->payment_status !== 'pending') {
                $synced++;
            }
        }

        Log::info("Sinkronisasi massal selesai: {$synced} transaksi diperbarui dari status tertunda");

        return $synced;
    }
}
