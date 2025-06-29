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
        \Log::info('Midtrans callback received', $request->all());

        $serverKey = config('midtrans.serverKey');
        $hashedKey = hash('sha512', $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($hashedKey !== $request->signature_key) {
            \Log::warning('Midtrans callback invalid signature', [
                'expected' => $hashedKey,
                'received' => $request->signature_key,
            ]);

            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;
        $transaction = Transaction::where('code', $orderId)->first();

        if (! $transaction) {
            \Log::warning('Midtrans callback transaction not found', ['order_id' => $orderId]);

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
            case 'capture':
                if ($request->payment_type == 'credit_card') {
                    if ($request->fraud_status == 'challenge') {
                        $transaction->update(['payment_status' => 'pending']);
                    } else {
                        $transaction->update(['payment_status' => 'success']);
                        if ($transaction->motorcycle && $transaction->motorcycle->status !== 'on_going') {
                            $transaction->motorcycle->decreaseStock(1);
                            $transaction->motorcycle->update([
                                'status' => 'on_going',
                            ]);
                        }
                    }
                } else {
                    $transaction->update(['payment_status' => 'success']);
                    if ($transaction->motorcycle && $transaction->motorcycle->status !== 'on_going') {
                        $transaction->motorcycle->decreaseStock(1);
                        $transaction->motorcycle->update([
                            'status' => 'on_going',
                        ]);
                    }
                }
                break;
            case 'settlement':
                $transaction->update(['payment_status' => 'success']);
                if ($transaction->motorcycle && $transaction->motorcycle->status !== 'on_going') {
                    $transaction->motorcycle->decreaseStock(1);
                    $transaction->motorcycle->update([
                        'status' => 'on_going',
                    ]);
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

        \Log::info('Midtrans callback processed', [
            'order_id' => $request->order_id,
            'transaction_status' => $request->transaction_status,
            'payment_status' => $transaction->fresh()->payment_status,
            'motor_status' => $transaction->motorcycle ? $transaction->motorcycle->fresh()->status : null,
            'timestamp' => now()->toDateTimeString(),
        ]);

        return response()->json(['message' => 'Callback received successfully']);
    }
}
