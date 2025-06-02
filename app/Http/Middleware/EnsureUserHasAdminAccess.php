<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (! $request->user() || ! $request->user()->hasAnyRole(['admin', 'kepalaUPTD'])) {
        return redirect('/')
            ->with('error', 'Kamu bukan admin.');
    }

    return $next($request);
    }
}
