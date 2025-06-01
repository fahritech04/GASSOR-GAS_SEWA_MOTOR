<?php

use App\Http\Controllers\MotorbikeRentalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilePenyewaController;
use App\Http\Controllers\ProfilePemilikController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;

Route::post('/login/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Select Role page
Route::get('/select-role', function () {
    return view('select-role');
})->name('select-role')->middleware('guest');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route khusus penyewa
Route::middleware(['auth', 'role:penyewa'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');
    Route::get('/city/{slug}', [CityController::class, 'show'])->name('city.show');
    Route::get('/motor/{slug}', [MotorbikeRentalController::class, 'show'])->name('motor.show');
    Route::get('/motor/{slug}/motorcycles', [MotorbikeRentalController::class, 'motorcycles'])->name('motor.motorcycles');
    Route::get('/find-motor', [MotorbikeRentalController::class, 'find'])->name('find-motor');
    Route::get('/find-results', [MotorbikeRentalController::class, 'findResults'])->name('find-motor.results');
    Route::get('/profile/penyewa', [ProfilePenyewaController::class, 'index'])->name('profile.penyewa');
    Route::get('/editprofile/penyewa', [ProfilePenyewaController::class, 'edit'])->name('editprofile.penyewa');
    Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi');
    Route::get('/motor/booking/{slug}', [BookingController::class, 'booking'])->name('booking');
    Route::get('/motor/booking/{slug}/information', [BookingController::class, 'information'])->name('booking.information');
    Route::post('/motor/booking/{slug}/information/save', [BookingController::class, 'saveInformation'])->name('booking.information.save');
    Route::get('/motor/booking/{slug}/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
    Route::post('/motor/booking/{slug}/payment', [BookingController::class, 'payment'])->name('booking.payment');
    Route::get('/booking-success', [BookingController::class, 'success'])->name('booking.success');
    Route::get('/check-booking', [BookingController::class, 'check'])->name('check-booking');
    Route::post('/check-booking', [BookingController::class, 'show'])->name('check-booking.show');
});

// Route khusus pemilik
Route::middleware(['auth', 'role:pemilik'])->group(function () {
    Route::get('/pemilik/dashboard', [PemilikController::class, 'index'])
    ->middleware(['auth', 'role:pemilik'])
    ->name('pemilik.dashboard');
    Route::get('/profile/pemilik', [ProfilePemilikController::class, 'index'])->name('profile.pemilik');
    Route::get('/editprofile/pemilik', [ProfilePemilikController::class, 'edit'])->name('editprofile.pemilik');
    Route::get('/pemilik/daftar-motor', [PemilikController::class, 'showDaftarMotor'])->name('pemilik.daftar-motor');
    Route::get('/pemilik/pesanan', [PemilikController::class, 'showPesanan'])->name('pemilik.pesanan');
    Route::get('/map', [MapController::class, 'showMap'])->name('map');
    Route::get('/api/gps', [MapController::class, 'getGps'])->name('api.gps');
});


Route::fallback(function () {
    $user = Auth::user();
    if ($user) {
        if ($user->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        } elseif ($user->role === 'penyewa') {
            return redirect()->route('home');
        }
    }
    // Jika belum login, redirect ke login
    return redirect()->route('login');
});
