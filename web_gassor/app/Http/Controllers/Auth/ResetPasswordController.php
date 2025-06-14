<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        // Jika user sudah login, redirect ke dashboard
        if (Auth::check()) {
            if (Auth::user()->role === 'pemilik') {
                return redirect()->route('pemilik.dashboard');
            } else {
                return redirect()->route('home');
            }
        }
        // Cek session flag, jika tidak ada redirect ke lupa password
        if (! session('can_reset_password')) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        // Jika user sudah login, redirect ke dashboard
        if (Auth::check()) {
            if (Auth::user()->role === 'pemilik') {
                return redirect()->route('pemilik.dashboard');
            } else {
                return redirect()->route('home');
            }
        }
        // Cek session flag, jika tidak ada redirect ke lupa password
        if (! session('can_reset_password')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->google_blocked = true; // blokir login Google setelah reset password
                $user->save();
                Auth::login($user);
            }
        );

        // Hapus flag setelah reset
        session()->forget('can_reset_password');

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
