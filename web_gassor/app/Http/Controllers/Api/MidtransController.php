<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.serverKey');
        $hashedKey = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;
        $transaction = Transaction::where('code', $orderId)->first();

        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);

        $messages =
            'Halo, '.$transaction->name.'!'.PHP_EOL.PHP_EOL.
            'Kami telah menerima pembayaran Anda dengan kode booking: '.$transaction->code.'.'.PHP_EOL.
            'Total pembayaran: Rp '.number_format($transaction->total_amount, 0, ',', '.').PHP_EOL.PHP_EOL.
            'Alamat: '.$transaction->motorbikeRental->address.PHP_EOL.
            'Mulai tanggal: '.date('d-m-y', strtotime($transaction->start_date)).PHP_EOL.PHP_EOL.
            'Terima kasih atas kepercayaan Anda! 😊'.PHP_EOL.
            'Kami tunggu kedatangan Anda.';

        switch ($transactionStatus) {
            // case 'capture':
            //     if ($request->payment_type == 'credit_card') {
            //         if ($request->fraud_status == 'challange') {
            //             $transaction->update(['payment_status' => 'pending']);
            //         } else {
            //             $transaction->update(['payment_status' => 'success']);
            //         }
            //     } else {
            //         $transaction->update(['payment_status' => 'success']);
            //     }
            //     break;
            // case 'settlement':
            //     $transaction->update(['payment_status' => 'success']);

            //     $twilio->messages
            //         ->create(
            //             "whatsapp:+" . $transaction->phone_number, // to
            //             array(
            //                 "from" => "whatsapp:+14155238886",
            //                 "body" => $messages
            //             )
            //         );

            //     break;
            case 'capture':
                if ($request->payment_type == 'credit_card') {
                    if ($request->fraud_status == 'challange') {
                        $transaction->update(['payment_status' => 'pending']);
                    } else {
                        $transaction->update(['payment_status' => 'success']);
                        // Set motor tidak tersedia
                        if ($transaction->motorcycle) {
                            $transaction->motorcycle->update(['is_available' => false]);
                        }
                    }
                } else {
                    $transaction->update(['payment_status' => 'success']);
                    // Set motor tidak tersedia
                    if ($transaction->motorcycle) {
                        $transaction->motorcycle->update(['is_available' => false]);
                    }
                }
                break;
            case 'settlement':
                $transaction->update(['payment_status' => 'success']);
                // Set motor tidak tersedia
                if ($transaction->motorcycle) {
                    $transaction->motorcycle->update(['is_available' => false]);
                }

                $twilio->messages
                    ->create(
                        'whatsapp:+'.$transaction->phone_number, // to
                        [
                            'from' => 'whatsapp:+14155238886',
                            'body' => $messages,
                        ]
                    );

                break;
            case 'pending':
                $transaction->update(['payment_status' => 'pending']);
                break;
            case 'deny':
                $transaction->update(['payment_status' => 'failed']);
                break;
            case 'expire':
                $transaction->update(['payment_status' => 'expired']);
                break;
            case 'cancel':
                $transaction->update(['payment_status' => 'canceled']);
                break;
            default:
                $transaction->update(['payment_status' => 'unknown']);
                break;
        }

        return response()->json(['message' => 'Callback received successfully']);
    }
}
