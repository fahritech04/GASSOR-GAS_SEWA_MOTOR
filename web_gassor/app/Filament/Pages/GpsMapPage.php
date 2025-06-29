<?php

namespace App\Filament\Pages;

use App\Http\Controllers\MapController;
use App\Models\Motorcycle;
use App\Models\Transaction;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class GpsMapPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static string $view = 'filament.pages.gps-map-page';

    protected static ?string $navigationGroup = 'Maps GPS';

    public $gpsData = null;

    public $motorcyclesWithGps = [];

    public $transaction = null;

    public $activeTransactions = [];

    public function mount($id = null)
    {
        $controller = new MapController;
        $this->gpsData = $controller->getGps()->getData();

        // Ambil motor yang sedang disewa (stok tersedia lebih kecil dari total stok)
        $this->motorcyclesWithGps = Motorcycle::with(['owner'])
            ->where('available_stock', '<', DB::raw('stock'))
            ->where('has_gps', true)
            ->get();

        // Ambil transaksi aktif untuk setiap motor
        $today = now()->format('Y-m-d');
        $motorIds = $this->motorcyclesWithGps->pluck('id')->toArray();

        if (! empty($motorIds)) {
            $this->activeTransactions = Transaction::whereIn('motorcycle_id', $motorIds)
                ->whereIn('payment_status', ['success', 'paid', 'SUCCESS', 'PAID'])
                ->where('start_date', '<=', $today)
                ->where(function ($query) use ($today) {
                    $query->whereRaw('DATE_ADD(start_date, INTERVAL duration DAY) >= ?', [$today])
                        ->orWhereNull('duration');
                })
                ->get()
                ->keyBy('motorcycle_id');
        }

        // Jika ada parameter id, ambil data transaction
        if ($id) {
            $this->transaction = Transaction::with(['motorcycle.images', 'motorcycle.owner'])
                ->where('id', $id)
                ->firstOrFail();
        }
    }

    public function getGpsData()
    {
        return $this->gpsData;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }

    public function getActiveTransactionForMotor($motorcycleId)
    {
        return $this->activeTransactions[$motorcycleId] ?? null;
    }
}
