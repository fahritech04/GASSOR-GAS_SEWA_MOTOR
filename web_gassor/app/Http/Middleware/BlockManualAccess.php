<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockManualAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Deteksi akses manual: tidak ada HTTP_REFERER atau referer bukan dari domain sendiri
        $referer = $request->headers->get('referer');
        $host = $request->getSchemeAndHttpHost();
        if (!$referer || strpos($referer, $host) !== 0) {
            // Redirect sesuai role jika sudah login
            if (Auth::check()) {
                if (Auth::user()->role === 'pemilik') {
                    return redirect()->route('pemilik.dashboard');
                } else {
                    return redirect()->route('home');
                }
            }
            // Jika belum login, redirect ke login
            return redirect()->route('login');
        }
        return $next($request);
    }
}
