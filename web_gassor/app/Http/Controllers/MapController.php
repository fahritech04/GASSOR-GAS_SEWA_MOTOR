<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Models\Transaction;

class MapController extends Controller
{
    public function showMap($id)
    {
        $url = env('FIREBASE_GPS_URL');
        $secret = env('FIREBASE_GPS_SECRET');
        $response = Http::get($url, ['auth' => $secret]);
        $gpsData = $response->json();

        $transaction = Transaction::with(['motorcycle.images', 'motorcycle.owner'])
            ->where('id', $id)
            ->firstOrFail();

        return view('pages.pemilik.map.maps', compact('gpsData', 'transaction'));
    }

    public function getGps()
    {
        $url = env('FIREBASE_GPS_URL');
        $secret = env('FIREBASE_GPS_SECRET');

        $response = Http::get($url, ['auth' => $secret]);

        return response()->json($response->json());
    }
}
