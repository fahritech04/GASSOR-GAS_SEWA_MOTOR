<?php

use App\Http\Controllers\MotorbikeRentalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilePenyewaController;
use App\Http\Controllers\InformasiController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;

Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::post('/login/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route hanya bisa diakses setelah login
Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show')->middleware('auth');
Route::get('/city/{slug}', [CityController::class, 'show'])->name('city.show')->middleware('auth');
Route::get('/motor/{slug}', [MotorbikeRentalController::class, 'show'])->name('motor.show')->middleware('auth');
Route::get('/motor/{slug}/motorcycles', [MotorbikeRentalController::class, 'motorcycles'])->name('motor.motorcycles')->middleware('auth');
Route::get('/find-motor', [MotorbikeRentalController::class, 'find'])->name('find-motor')->middleware('auth');
Route::get('/find-results', [MotorbikeRentalController::class, 'findResults'])->name('find-motor.results')->middleware('auth');

// Route khusus penyewa
Route::middleware(['auth', 'role:penyewa'])->group(function () {
    Route::get('/profile/penyewa', [ProfilePenyewaController::class, 'index'])->name('profile.penyewa')->middleware('auth');
    Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi')->middleware('auth');
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
    // Contoh route khusus pemilik
    // Route::get('/pemilik/dashboard', [PemilikController::class, 'dashboard'])->name('pemilik.dashboard');
});
