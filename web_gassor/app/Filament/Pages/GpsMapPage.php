<?php

namespace App\Filament\Pages;

use App\Http\Controllers\MapController;
use App\Models\Transaction;
use Filament\Pages\Page;

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

        // Ambil semua transaksi aktif dengan motor yang memiliki GPS
        $this->activeTransactions = Transaction::with(['motorcycle.owner'])
            ->whereHas('motorcycle', function ($query) {
                $query->where('has_gps', true);
            })
            ->whereIn('payment_status', ['success', 'paid', 'SUCCESS', 'PAID'])
            ->where('rental_status', 'on_going')
            ->get();

        $this->motorcyclesWithGps = $this->activeTransactions
            ->map(function ($transaction) {
                $motorcycle = $transaction->motorcycle;
                $motorcycle->renter_name = $transaction->name;
                $motorcycle->transaction_id = $transaction->id;

                return $motorcycle;
            })
            ->unique('id')
            ->values();

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
        return $this->activeTransactions->where('motorcycle_id', $motorcycleId)->first();
    }

    public function getActiveTransactions()
    {
        return $this->activeTransactions;
    }
}
