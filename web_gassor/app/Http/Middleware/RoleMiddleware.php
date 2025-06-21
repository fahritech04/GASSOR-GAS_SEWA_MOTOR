<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect('/login');
        }

        if (! in_array($user->role, $roles)) {
            // Hindari redirect loop
            if ($user->role === 'pemilik' && $request->routeIs('pemilik.dashboard')) {
                abort(403, 'Akses ditolak.');
            } elseif ($user->role === 'penyewa' && $request->routeIs('home')) {
                abort(403, 'Akses ditolak.');
            }
            // Redirect ke dashboard sesuai role
            if ($user->role === 'pemilik') {
                return redirect()->route('pemilik.dashboard');
            } elseif ($user->role === 'penyewa') {
                return redirect()->route('home');
            } else {
                return redirect('/login');
            }
        }

        return $next($request);
    }
}
