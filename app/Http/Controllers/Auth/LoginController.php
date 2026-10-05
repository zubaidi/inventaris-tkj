<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = 'login:'.strtolower($request->email).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($key);

            $user = Auth::user();

            // Cek status approval
            if ($user->status === 'pending') {
                Auth::logout();

                return back()->withErrors(['email' => 'Akun lu masih nunggu approval Super Admin.']);
            }

            if ($user->status === 'rejected') {
                Auth::logout();

                return back()->withErrors(['email' => 'Akun lu ditolak. Hubungi Super Admin.']);
            }

            $request->session()->regenerate();

            // 👇 Redirect sesuai role
            if ($user->isSuperAdmin() || $user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->isPimpinan()) {
                // Kepala Sekolah & Waka → dashboard (read-only)
                return redirect()->intended(route('admin.dashboard'));
            }

            // User biasa
            return redirect()->intended(route('admin.inventaris.index'));
        }

        RateLimiter::hit($key, 60);

        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
