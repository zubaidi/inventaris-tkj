<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPimpinan
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect('/login');
        }

        if (! $request->user()->isPimpinan()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Halaman ini khusus pimpinan.');
        }

        return $next($request);
    }
}
