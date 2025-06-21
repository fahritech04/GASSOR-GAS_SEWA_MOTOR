<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        // Jika tidak ada role di query, redirect ke select-role
        if (! $request->has('role') || ! in_array($request->role, ['penyewa', 'pemilik'])) {
            return redirect()->route('select-role');
        }

        return view('auth.login');
        // return view('select-role');
    }

    public function showRegister(Request $request)
    {
        if (! $request->has('role') || ! in_array($request->role, ['penyewa', 'pemilik'])) {
            return redirect()->route('select-role');
        }
        return view('auth.register');
    }

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

            $user = Auth::user();
            if ($user->role === 'pemilik') {
                return redirect()->route('pemilik.dashboard');
            } elseif ($user->role === 'penyewa') {
                return redirect()->route('home');
            } else {
                Auth::logout();

                return back()->withErrors(['email' => 'Role tidak valid.']);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:penyewa,pemilik',
        ]);

        $user = User::create([
            'name' => null,
            'email' => $request->email,
            'phone' => null,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'username' => null,
            'profile_image_url' => null,
            'tempat_lahir' => null,
            'tanggal_lahir' => null,
            'ktp_image_url' => null,
            'sim_image_url' => null,
            'ktm_image_url' => null,
            'google_blocked' => false,
        ]);

        Auth::login($user);

        if ($user->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        } else {
            return redirect()->route('home');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget('google_role');

        return redirect('/');
    }

    public function redirectToGoogle(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:pemilik,penyewa'],
        ]);
        session(['google_role' => $request->role]);

        // Google selalu tampilkan pilihan akun
        return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            session()->forget('google_role');
            return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google.']);
        }

        $role = session('google_role', 'penyewa');
        session()->forget('google_role');

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            if ($user->google_blocked) {
                return redirect('/login')->withErrors([
                    'email' => 'Akun ini sudah tidak bisa login/daftar dengan Google karena Anda sudah pernah reset password. Silakan login dengan email & password.',
                ]);
            }
            if ($user->role !== $role) {
                return redirect('/login')->withErrors([
                    'email' => 'Akun ini sudah terdaftar sebagai '.ucfirst($user->role).'. Anda hanya bisa menggunakan satu akun Google untuk satu role. Silakan login sesuai role yang sudah terdaftar.',
                ]);
            }
            // role sama, lanjutkan login
            Auth::login($user, true);
            request()->session()->regenerate();
        } else {
            // Email belum ada, buat baru
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'User',
                'username' => $googleUser->getNickname() ?? null,
                'email' => $googleUser->getEmail(),
                'profile_image_url' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(16)),
                'role' => $role,
            ]);
            Auth::login($user, true);
            request()->session()->regenerate();
        }

        // Cek role sebelum redirect
        if ($user->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        } elseif ($user->role === 'penyewa') {
            return redirect()->route('home');
        } else {
            Auth::logout();
            return redirect('/login')->withErrors(['email' => 'Role tidak valid.']);
        }
    }
}
