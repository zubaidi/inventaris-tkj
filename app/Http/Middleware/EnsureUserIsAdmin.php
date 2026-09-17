<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')   // ← route 'login' harus ada
                ->with('error', 'Silakan login dulu.');
        }

        if (! $request->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang boleh masuk.');
        }

        return $next($request);
    }
}
