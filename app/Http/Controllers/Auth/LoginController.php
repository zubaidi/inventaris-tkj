<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $html = <<<'HTML'
            <!doctype html>
            <html lang="id"><head><meta charset="utf-8"><title>Login</title></head>
            <body><form method="post" action="/login">
                <input type="hidden" name="_token" value="%s">
                <label>Email <input type="email" name="email" required></label>
                <label>Password <input type="password" name="password" required></label>
                <button type="submit">Login</button>
            </form></body></html>
            HTML;

        return response(str_replace('%s', csrf_token(), $html), 200, ['Content-Type' => 'text/html']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
