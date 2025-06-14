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
