<?php

namespace App\Http\Controllers;

use App\Models\MotorcycleReview;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MotorcycleReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);

        // Middleware hanya penyewa yang bisa create/store/destroy
        $this->middleware(function ($request, $next) {
            if ($request->route()->getActionMethod() !== 'show' && auth()->user()->role !== 'penyewa') {
                return redirect()->back()->with('error', 'Hanya penyewa yang dapat memberikan review.');
            }
            return $next($request);
        })->except(['show']);
    }

    public function create(Transaction $transaction)
    {
        // Validasi bahwa user bisa membuat review untuk transaksi ini
        if (!$transaction->can_be_reviewed) {
            return redirect()->route('history-booking')->with('error', 'Transaksi ini tidak dapat direview.');
        }

        // Pastikan user adalah penyewa dari transaksi ini
        if ($transaction->email !== auth()->user()->email) {
            return redirect()->route('history-booking')->with('error', 'Anda tidak memiliki akses untuk mereview transaksi ini.');
        }

        $transaction->load('motorcycle.images', 'motorcycle.motorbikeRental');

        return view('pages.penyewa.review.create', compact('transaction'));
    }

    /**
     * Menyimpan review baru
     */
    public function store(Request $request, Transaction $transaction)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('history-booking')
                           ->withErrors($validator)
                           ->with('error', 'Data review tidak valid. Silakan periksa kembali.');
        }

        // Validasi bahwa user bisa membuat review untuk transaksi ini
        if (!$transaction->can_be_reviewed) {
            return redirect()->route('history-booking')->with('error', 'Transaksi ini tidak dapat direview.');
        }

        // Pastikan user adalah penyewa dari transaksi ini
        if ($transaction->email !== auth()->user()->email) {
            return redirect()->route('history-booking')->with('error', 'Anda tidak memiliki akses untuk mereview transaksi ini.');
        }

        try {
            DB::beginTransaction();

            MotorcycleReview::create([
                'motorcycle_id' => $transaction->motorcycle_id,
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);

            DB::commit();

            return redirect()->route('history-booking')->with('success', 'Review berhasil ditambahkan. Terima kasih atas feedback Anda!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('history-booking')->with('error', 'Terjadi kesalahan saat menyimpan review. Silakan coba lagi.');
        }
    }

    /**
     * Menampilkan semua review untuk motor tertentu
     */
    public function show($motorcycleId)
    {
        $motorcycle = \App\Models\Motorcycle::with([
            'reviews.user',
            'reviews' => function($query) {
                $query->latest();
            },
            'images',
            'motorbikeRental'
        ])->findOrFail($motorcycleId);

        return view('pages.motorcycle.reviews', compact('motorcycle'));
    }

    /**
     * Menghapus review (hanya pemilik review yang bisa menghapus)
     */
    public function destroy(MotorcycleReview $review)
    {
        if ($review->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus review ini.');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Review berhasil dihapus.');
    }
}
