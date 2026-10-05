<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanWrite
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect('/login');
        }

        // Pimpinan read-only
        if ($user->isPimpinan()) {
            return redirect()
                ->back()
                ->with('error', 'Pimpinan cuma bisa lihat, nggak bisa edit data.');
        }

        return $next($request);
    }
}
