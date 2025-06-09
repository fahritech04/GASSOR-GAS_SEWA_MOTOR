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
    public function showLogin(Request $request)
    {
        // Jika tidak ada role di query, redirect ke select-role
        if (!$request->has('role') || !in_array($request->role, ['penyewa', 'pemilik'])) {
            return redirect()->route('select-role');
        }
        return view('auth.login');
        // return view('select-role');
    }

    public function showRegister()
    {
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

        // Redirect sesuai role
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
        return redirect('/login');
    }

    // --- GOOGLE LOGIN ---
    public function redirectToGoogle(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:pemilik,penyewa'],
        ]);
        session(['google_role' => $request->role]);
        return Socialite::driver('google')->redirect();
    }

    // public function handleGoogleCallback()
    // {
    //     try {
    //         $googleUser = Socialite::driver('google')->user();
    //     } catch (\Exception $e) {
    //         return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google.']);
    //     }

    //     $role = session('google_role', 'penyewa'); // default penyewa jika tidak ada

    //     $user = User::where('email', $googleUser->getEmail())->first();

    //     if (!$user) {
    //         $user = User::create([
    //             'name' => $googleUser->getName() ?? $googleUser->getNickname(),
    //             'username' => $googleUser->getNickname() ?? null,
    //             'email' => $googleUser->getEmail(),
    //             'profile_image_url' => $googleUser->getAvatar(),
    //             'password' => bcrypt(Str::random(16)),
    //             'role' => $role,
    //         ]);
    //     } else {
    //         // Update role jika berbeda
    //         if ($user->role !== $role) {
    //             $user->role = $role;
    //             $user->save();
    //         }
    //     }

    //     Auth::login($user, true);

    //     // Redirect sesuai role
    //     if ($user->role === 'pemilik') {
    //         return redirect()->route('pemilik.dashboard');
    //     } else {
    //         return redirect()->route('home');
    //     }
    // }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['email' => 'Gagal login dengan Google.']);
        }

        $role = session('google_role', 'penyewa'); // default penyewa jika tidak ada

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Jika user sudah pernah reset password, blokir login/daftar Google
            if ($user->google_blocked) {
                return redirect('/login')->withErrors([
                    'email' => 'Akun ini sudah tidak bisa login/daftar dengan Google karena Anda sudah pernah reset password. Silakan login dengan email & password.'
                ]);
            }
            // Jika email sudah ada, cek role-nya
            if ($user->role !== $role) {
                // Email sudah terdaftar dengan role lain, tolak login/daftar
                return redirect('/login')->withErrors([
                    'email' => 'Akun ini sudah terdaftar sebagai ' . ucfirst($user->role) . '. Anda hanya bisa menggunakan satu akun Google untuk satu role. Silakan login sesuai role yang sudah terdaftar.'
                ]);
            }
            // Jika role sama, lanjutkan login
        } else {
            // Email belum ada, buat user baru
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname(),
                'username' => $googleUser->getNickname() ?? null,
                'email' => $googleUser->getEmail(),
                'profile_image_url' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(16)),
                'role' => $role,
            ]);
        }

        Auth::login($user, true);

        // Redirect sesuai role
        if ($user->role === 'pemilik') {
            return redirect()->route('pemilik.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
}
