<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SiswaSessionAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah ada session siswa_id
        if (!Session::has('siswa_id')) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login sebagai siswa terlebih dahulu!');
        }

        return $next($request);
    }
}