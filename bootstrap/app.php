<?php

use App\Http\Middleware\CheckOperationalHours;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
// Import Middleware
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware; // <-- PERHATIAN: Cek folder app/Http/Middleware/ kamu. Kalau nama filenya CheckRole.php, ubah tulisan RoleMiddleware ini jadi CheckRole

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // 1. Daftarkan alias untuk 'role'
        $middleware->alias([
            'role' => RoleMiddleware::class, // Sesuaikan dengan nama filemu
        ]);

        // 2. UBAH DI SINI: Masukkan ke grup 'web' agar Auth & Session bisa terbaca!
        $middleware->appendToGroup('web', CheckOperationalHours::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
