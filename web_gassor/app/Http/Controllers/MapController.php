<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MapController extends Controller
{
    public function showMap()
    {
        return view('pages.pemilik.map.maps');
    }

    // public function getGps()
    // {
    //     $url = 'https://gpsiot-cf1d6-default-rtdb.asia-southeast1.firebasedatabase.app/tracking.json';
    //     $secret = 'zJGSaE8g6JfJWmtINaUf2qTGSUGmFtnhkPSubE15';

    //     $response = Http::get($url, ['auth' => $secret]);
    //     return response()->json($response->json());
    // }

    public function getGps()
    {
        $url = env('FIREBASE_GPS_URL');
        $secret = env('FIREBASE_GPS_SECRET');

        $response = Http::get($url, ['auth' => $secret]);
        return response()->json($response->json());
    }
}
