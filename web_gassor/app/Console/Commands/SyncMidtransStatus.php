<?php

namespace App\Console\Commands;

use App\Services\MidtransStatusSyncService;
use Illuminate\Console\Command;

class SyncMidtransStatus extends Command
{
    protected $signature = 'midtrans:sync-status {--force : Force sync all pending transactions}';
    protected $description = 'Sinkronisasi status pembayaran dengan Midtrans untuk transaksi pending';

    public function handle()
    {
        $this->info('Memulai sinkronisasi status Midtrans...');

        $syncService = new MidtransStatusSyncService();
        $synced = $syncService->syncPendingTransactions();

        $this->info("✅ Sinkronisasi selesai!");
        $this->info("📊 {$synced} transaksi diperbarui dari status tertunda");

        return Command::SUCCESS;
    }
}
