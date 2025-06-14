<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm(Request $request)
    {
        // Cek apakah request datang dari klik link (ada HTTP_REFERER atau AJAX)
        $referer = $request->headers->get('referer');
        $isAjax = $request->ajax() || $request->wantsJson();
        if (! $referer && ! $isAjax) {
            return redirect()->route('login');
        }
        // Set flag untuk akses reset password
        session(['can_reset_password' => true]);

        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
