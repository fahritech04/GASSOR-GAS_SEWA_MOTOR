<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Motorcycle;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getTransactionDataFormSession()
    {
        return session()->get('transaction');
    }

    public function saveTransactionDataToSession($data)
    {
        $transaction = session()->get('transaction', []);

        foreach ($data as $key => $value) {
            $transaction[$key] = $value;
        }

        session()->put('transaction', $transaction);
    }

    public function saveTransaction($data)
    {
        // Validasi data yang diperlukan
        if (!isset($data['motorcycle_id']) || empty($data['motorcycle_id'])) {
            throw new \Exception('Motorcycle ID is required but missing from transaction data');
        }

        $motorcycle = Motorcycle::find($data['motorcycle_id']);

        if (!$motorcycle) {
            throw new \Exception('Motorcycle not found with ID: ' . $data['motorcycle_id']);
        }

        $data = $this->prepareTransactionData($data, $motorcycle);

        // Add motorbike_rental_id from the motorcycle
        $data['motorbike_rental_id'] = $motorcycle->motorbike_rental_id;

        $transaction = Transaction::create($data);
        session()->forget('transaction');

        return $transaction;
    }

    public function getTransactionByCode($code)
    {
        return Transaction::where('code', $code)->first();
    }

    public function getTransactionByCodeEmailPhone($code, $email, $phone)
    {
        return Transaction::where('code', $code)->where('email', $email)->where('phone_number', $phone)->first();
    }

    public function prepareTransactionData($data, $motorcycle)
    {
        $data['code'] = $this->generateTransactionCode();
        $data['payment_status'] = 'pending';
        $data['rental_status'] = 'pending';
        $data['transaction_date'] = now();
        $data['motorbike_rental_id'] = $motorcycle->motorbike_rental_id;

        $total = $this->calculateTotalAmount($motorcycle->price_per_day, $data['duration']);
        $data['total_amount'] = $total;
        $data['payment_method'] = 'full_payment';

        $data['start_time'] = $data['start_time'] ?? null;
        $data['end_time'] = $data['end_time'] ?? null;

        return $data;
    }

    public function generateTransactionCode()
    {
        return 'GASMTR'.rand(100000, 999999);
    }

    public function calculateTotalAmount($pricePerMonth, $duration)
    {
        $subtotal = $pricePerMonth * $duration;

        // $tax = $subtotal * 0.11;
        // $insurance = $subtotal * 0.01;
        // return $subtotal + $tax + $insurance;
        return $subtotal;
    }

    public function calculatePaymentAmount($total, $paymentMethod)
    {
        return $total;
    }

    public function getLatestTransactionsByOwner($ownerId, $limit = 10)
    {
        return Transaction::with(['motorcycle', 'motorcycle.owner'])
            ->whereHas('motorcycle', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getTransactionsByUser($userId)
    {
        // Transaksi berdasarkan nama/email user (penyewa)
        return Transaction::with(['motorcycle', 'motorcycle.owner', 'motorbikeRental'])
            ->where(function ($query) {
                $query->where('email', auth()->user()->email)
                      ->orWhere('name', auth()->user()->name);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public function getActiveTransactionsByUser($userId)
    {
        // Transaksi aktif (payment_status success + rental_status on_going) dan pending
        $query = Transaction::with(['motorcycle', 'motorcycle.owner', 'motorbikeRental'])
            ->where(function ($query) {
                $query->where('email', auth()->user()->email)
                      ->orWhere('name', auth()->user()->name);
            })
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    // Payment success dan rental on_going
                    $subQuery->where('payment_status', 'success')
                             ->where('rental_status', 'on_going');
                })->orWhere('payment_status', 'pending');
            })
            ->orderByDesc('created_at');

        return $query->get();
    }

    public function getHistoryTransactionsByUser($userId)
    {
        // History transaksi (kecuali payment_status success + rental_status on_going dan pending)
        return Transaction::with(['motorcycle', 'motorcycle.owner', 'motorbikeRental'])
            ->where(function ($query) {
                $query->where('email', auth()->user()->email)
                      ->orWhere('name', auth()->user()->name);
            })
            ->where(function ($query) {
                $query->where('payment_status', '!=', 'pending')
                      ->where('payment_status', '!=', 'success')
                      ->orWhere(function ($subQuery) {
                          $subQuery->where('payment_status', 'success')
                                   ->where('rental_status', '!=', 'on_going');
                      });
            })
            ->orderByDesc('created_at')
            ->get();
    }
}
