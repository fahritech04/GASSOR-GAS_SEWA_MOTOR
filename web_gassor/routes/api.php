<?php

use App\Http\Controllers\Api\MidtransController;
use App\Http\Controllers\MapController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/midtrans-callback', [MidtransController::class, 'callback']);

// Route untuk GPS data
Route::get('/gps', [MapController::class, 'getGps']);

// Test route
Route::get('/test-gps', function () {
    return response()->json(['test' => 'GPS API working', 'timestamp' => now()]);
});
