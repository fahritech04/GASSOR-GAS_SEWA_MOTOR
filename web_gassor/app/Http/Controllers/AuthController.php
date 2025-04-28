<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $request->session()->regenerate();
    //         return redirect()->intended('/'); // Selalu ke home
    //     }

    //     return back()->withErrors([
    //         'email' => 'Email atau password salah.',
    //     ])->onlyInput('email');
    // }

    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => ['required', 'email'],
    //         'password' => ['required'],
    //         'role' => ['required', 'in:pemilik,penyewa'],
    //     ]);

    //     // Cari user berdasarkan email
    //     $user = User::where('email', $credentials['email'])->first();

    //     if (!$user) {
    //         return back()->withErrors([
    //             'email' => 'Email yang anda tulis salah.',
    //         ])->onlyInput('email');
    //     }

    //     // Cek password
    //     if (!\Hash::check($credentials['password'], $user->password)) {
    //         return back()->withErrors([
    //             'email' => 'Password yang anda tulis salah.',
    //         ])->onlyInput('email');
    //     }

    //     // Cek role
    //     if ($user->role !== $credentials['role']) {
    //         return back()->withErrors([
    //             'email' => 'Role yang anda pilih salah.',
    //         ])->onlyInput('email');
    //     }

    //     // Jika semua benar, login
    //     Auth::login($user);
    //     $request->session()->regenerate();
    //     return redirect()->intended('/');
    // }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:penyewa,pemilik'],
        ]);

        if (Auth::guard('web')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => $credentials['role'],
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:pemilik,penyewa',
        ]);

        $user = User::create([
            'name' => $request->fullname,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // public function redirectToGoogle()
    // {
    //     return Socialite::driver('google')->redirect();
    // }

    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->user();
    //     } catch (\Exception $e) {
    //         return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google.']);
    //     }

    //     // Cari user berdasarkan email
    //     $user = \App\Models\User::where('email', $googleUser->getEmail())->first();

    //     if (!$user) {
    //         // Jika user belum ada, buat user baru (default role: penyewa)
    //         $user = \App\Models\User::create([
    //             'name' => $googleUser->getName() ?? $googleUser->getNickname(),
    //             'email' => $googleUser->getEmail(),
    //             'password' => bcrypt(Str::random(16)), // password random
    //             'role' => 'penyewa', // default, bisa diubah sesuai kebutuhan
    //         ]);
    //     }

    //     Auth::login($user, true);

    //     return redirect('/');
    // }

    public function redirectToGoogle(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:pemilik,penyewa'],
        ]);
        session(['google_role' => $request->role]);
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google.']);
        }

        $role = session('google_role', 'penyewa'); // default penyewa jika tidak ada

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'role' => $role,
            ]);
        } else {
            // Update role jika berbeda
            if ($user->role !== $role) {
                $user->role = $role;
                $user->save();
            }
        }

        Auth::login($user, true);

        return redirect('/');
    }
}
