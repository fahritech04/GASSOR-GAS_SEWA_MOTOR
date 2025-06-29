<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingShowRequest;
use App\Http\Requests\CustomerInformationStoreRequest;
use App\Interfaces\MotorbikeRentalRepositoryInterface;
use App\Interfaces\TransactionRepositoryInterface;
use App\Services\MidtransStatusSyncService;
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

    public function booking(Request $request, $slug)
    {
        $motorcycle = $this->motorbikeRentalRepository->getMotorbikeRentalMotorcycleById($request->motorcycle_id);
        if (! $motorcycle || ! $motorcycle->isAvailable()) {
            return redirect()->back()->with('error', 'Motor sudah tidak tersedia.');
        }
        $this->transactionRepository->saveTransactionDataToSession($request->all());

        return redirect()->route('booking.information', $slug);
    }

    public function information($slug)
    {
        $transaction = $this->transactionRepository->getTransactionDataFormSession();
        if (! $transaction || ! isset($transaction['motorcycle_id'])) {
            return redirect()->route('home')->with('error', 'Data booking tidak ditemukan atau sudah kadaluarsa.');
        }
        $motorbikeRental = $this->motorbikeRentalRepository->getMotorbikeRentalBySlug($slug);
        $motorcycle = $this->motorbikeRentalRepository->getMotorbikeRentalMotorcycleById($transaction['motorcycle_id']);

        return view('pages.booking.information', compact('transaction', 'motorbikeRental', 'motorcycle'));
    }

    public function saveInformation(CustomerInformationStoreRequest $request, $slug)
    {
        $data = $request->validated();
        // end_time otomatis 24 jam setelah start_time
        if (isset($data['start_time'])) {
            $start = \Carbon\Carbon::createFromFormat('H:i', $data['start_time']);
            $end = $start->copy()->addDay();
            $data['end_time'] = $end->format('H:i');
        }
        $this->transactionRepository->saveTransactionDataToSession($data);

        return redirect()->route('booking.checkout', $slug);
    }

    public function checkout($slug)
    {
        $transaction = $this->transactionRepository->getTransactionDataFormSession();
        if (! $transaction || ! isset($transaction['motorcycle_id'])) {
            return redirect()->route('home')->with('error', 'Data booking tidak ditemukan atau sudah kadaluarsa.');
        }
        $motorbikeRental = $this->motorbikeRentalRepository->getMotorbikeRentalBySlug($slug);
        $motorcycle = $this->motorbikeRentalRepository->getMotorbikeRentalMotorcycleById($transaction['motorcycle_id']);

        return view('pages.booking.checkout', compact('transaction', 'motorbikeRental', 'motorcycle'));
    }

    public function payment(Request $request)
    {
        $this->transactionRepository->saveTransactionDataToSession($request->all());

        $transaction = $this->transactionRepository->saveTransaction($this->transactionRepository->getTransactionDataFormSession());

        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
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
        $transaction->snap_url = $paymentUrl;
        $transaction->save();

        return redirect($paymentUrl);
    }

    public function success(Request $request)
    {
        $transaction = $this->transactionRepository->getTransactionByCode($request->order_id);

        if (! $transaction) {
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

        if (! $transaction) {
            return redirect()->back()->with('error', 'Data Transaksi Tidak Ditemukan');
        }

        // return view('pages.booking.detail', compact('transaction'));
        return view('pages.booking.detail', [
            'transaction' => $transaction,
            'motorbikeRental' => $transaction->motorbikeRental,
        ]);
    }

    public function retryPayment(Request $request, $code)
    {
        $transaction = $this->transactionRepository->getTransactionByCode($code);
        if (! $transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }
        // generate code/order_id baru
        $newOrderId = $this->transactionRepository->generateTransactionCode();
        $transaction->code = $newOrderId;
        $transaction->payment_status = 'pending';
        $transaction->save();
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
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
        $transaction->snap_url = $paymentUrl;
        $transaction->save();

        return redirect($paymentUrl);
    }

    public function cancel(Request $request, $code)
    {
        $transaction = $this->transactionRepository->getTransactionByCode($code);
        if (! $transaction || ! in_array(strtolower($transaction->payment_status), ['failed', 'expired', 'pending'])) {
            return redirect()->back()->with('error', 'Transaksi tidak valid untuk dibatalkan.');
        }

        // Update both payment and rental status
        $transaction->update([
            'payment_status' => 'canceled',
            'rental_status' => 'cancelled'
        ]);

        return redirect()->route('check-booking')->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function paymentStatus(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = $this->transactionRepository->getTransactionByCode($orderId);
        if (! $transaction) {
            return redirect()->route('home')->with('error', 'Transaksi tidak ditemukan.');
        }

        // Gunakan service untuk sinkronisasi yang lebih reliable
        if (! in_array(strtolower($transaction->payment_status), ['success', 'canceled', 'expired', 'failed'])) {
            $syncService = new MidtransStatusSyncService;
            $transaction = $syncService->syncPaymentStatus($transaction);
        }

        $status = strtolower($transaction->payment_status);
        if ($status === 'success') {
            return redirect()->route('booking.success', ['order_id' => $orderId]);
        }

        // halaman status spesifik
        return view('pages.booking.status', [
            'transaction' => $transaction,
            'status' => $status,
        ]);
    }
}
