<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user adalah admin
        if (auth()->check() && auth()->user()->peran === 'admin') {
            return $next($request);
        }

        // Jika bukan admin, redirect dengan pesan error
        return redirect()->route('dashboard')
                       ->with('error', 'Anda tidak memiliki akses ke halaman ini. Hanya admin yang diizinkan.');
    }
}

// Daftarkan middleware di app/Http/Kernel.php atau bootstrap/app.php (Laravel 11+)
// Untuk Laravel 10 ke bawah, tambahkan di $routeMiddleware di app/Http/Kernel.php:
// 'admin' => \App\Http\Middleware\AdminMiddleware::class,

// Untuk Laravel 11+, daftarkan di bootstrap/app.php:
/*
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
*/