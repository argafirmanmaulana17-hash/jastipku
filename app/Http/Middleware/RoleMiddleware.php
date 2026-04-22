<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Cek apakah user punya role yang diperlukan.
     *
     * Cara pakai di routes:
     * Route::middleware('role:admin')
     * Route::middleware('role:jastiper')
     * Route::middleware('role:admin,jastiper') — multi role
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect('/login');
        }

        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'Akses ditolak. Kamu tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
