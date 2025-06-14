<?php

namespace App\Http\Controllers;

use App\Models\Motorcycle;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class PemilikController extends Controller
{
    // public function index()
    // {
    //     $motorcycles = Motorcycle::where('owner_id', Auth::id())->get();
    //     return view('pages.pemilik.dashboard', compact('motorcycles'));
    // }

    public function index()
    {
        $motorcycles = Motorcycle::where('owner_id', Auth::id())->get();

        // Ambil semua id motor milik owner
        $motorcycleIds = $motorcycles->pluck('id');

        // Ambil transaksi terbaru untuk motor-motor tersebut (misal 5 terakhir)
        $transactions = Transaction::with(['motorcycle', 'motorcycle.owner'])
            ->whereIn('motorcycle_id', $motorcycleIds)
            ->latest()
            ->take(5)
            ->get();

        $activeOrders = $transactions->where('payment_status', 'success')->count();

        $totalIncome = $transactions
            ->where('payment_status', 'success')
            ->sum(function ($transaction) {
                // Hitung durasi hari sewa
                $start = \Carbon\Carbon::parse($transaction->start_date);
                $end = \Carbon\Carbon::parse($transaction->end_date);
                $days = $start->diffInDays($end) ?: 1; // minimal 1 hari

                return $transaction->motorcycle->price_per_day * $days;
            });

        return view('pages.pemilik.dashboard', compact('motorcycles', 'transactions', 'activeOrders', 'totalIncome'));
    }

    public function showDaftarMotor()
    {
        $motorcycles = Motorcycle::where('owner_id', auth()->id())->get();

        return view('pages.pemilik.daftar-motor.showDaftarMotor', compact('motorcycles'));
    }

    public function showPesanan(\App\Repositories\TransactionRepository $transactionRepository)
    {
        $ownerId = auth()->id();
        $transactions = $transactionRepository->getLatestTransactionsByOwner($ownerId, 10);

        return view('pages.pemilik.pesanan.showPesanan', compact('transactions'));
    }
}
