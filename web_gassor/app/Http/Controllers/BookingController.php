<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingShowRequest;
use App\Http\Requests\CustomerInformationStoreRequest;
use App\Interfaces\MotorbikeRentalRepositoryInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Models\MotorbikeRental;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private MotorbikeRentalRepositoryInterface $motorbikeRentalRepository;
    private TransactionRepositoryInterface $transactionRepository;

    public function __construct(
        MotorbikeRentalRepositoryInterface $motorbikeRentalRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->motorbikeRentalRepository = $motorbikeRentalRepository;
        $this->transactionRepository = $transactionRepository;
    }

    // public function booking(Request $request, $slug)
    // {
    //     $this->transactionRepository->saveTransactionDataToSession($request->all());

    //     return redirect()->route('booking.information', $slug);
    // }

    public function booking(Request $request, $slug)
    {
        $motorcycle = $this->motorbikeRentalRepository->getMotorbikeRentalMotorcycleById($request->motorcycle_id);
        if (!$motorcycle || !$motorcycle->is_available) {
            return redirect()->back()->with('error', 'Motor sudah tidak tersedia.');
        }
        $this->transactionRepository->saveTransactionDataToSession($request->all());
        return redirect()->route('booking.information', $slug);
    }

    public function information($slug)
    {
        $transaction = $this->transactionRepository->getTransactionDataFormSession();
        $motorbikeRental = $this->motorbikeRentalRepository->getMotorbikeRentalBySlug($slug);
        $motorcycle = $this->motorbikeRentalRepository->getMotorbikeRentalMotorcycleById($transaction['motorcycle_id']);

        return view('pages.booking.information', compact('transaction', 'motorbikeRental', 'motorcycle'));
    }

    public function saveInformation(CustomerInformationStoreRequest $request, $slug)
    {
        $data = $request->validated();

        $this->transactionRepository->saveTransactionDataToSession($data);

        return redirect()->route('booking.checkout', $slug);
    }

    public function checkout($slug)
    {
        $transaction = $this->transactionRepository->getTransactionDataFormSession();
        $motorbikeRental = $this->motorbikeRentalRepository->getMotorbikeRentalBySlug($slug);
        $motorcycle = $this->motorbikeRentalRepository->getMotorbikeRentalMotorcycleById($transaction['motorcycle_id']);

        return view('pages.booking.checkout', compact('transaction', 'motorbikeRental', 'motorcycle'));
    }

    public function payment(Request $request)
    {
        $this->transactionRepository->saveTransactionDataToSession($request->all());

        $transaction = $this->transactionRepository->saveTransaction($this->transactionRepository->getTransactionDataFormSession());

        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->code,
                'gross_amount' => $transaction->total_amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->name,
                'email' => $transaction->email,
                'phone' => $transaction->phone_number,
            ],
        ];

        $paymentUrl = \Midtrans\Snap::createTransaction($params)->redirect_url;

        return redirect($paymentUrl);
    }

    public function success(Request $request)
    {
        $transaction = $this->transactionRepository->getTransactionByCode($request->order_id);

        if (!$transaction) {
            return redirect()->route('home');
        }

        return view('pages.booking.success', compact('transaction'));
    }

    public function check()
    {
        // Ambil transaksi milik user yang sedang login (atau tampilkan kosong jika guest)
        $transactions = [];
        if (auth()->check()) {
            $transactions = $this->transactionRepository->getTransactionsByUser(auth()->user()->id);
        }
        return view('pages.booking.check-booking', compact('transactions'));
    }

    public function show(BookingShowRequest $request)
    {
        $transaction = $this->transactionRepository->getTransactionByCodeEmailPhone($request->code, $request->email, $request->phone_number);

        if (!$transaction) {
            return redirect()->back()->with('error', 'Data Transaksi Tidak Ditemukan');
        }

        return view('pages.booking.detail', compact('transaction'));
    }
}
