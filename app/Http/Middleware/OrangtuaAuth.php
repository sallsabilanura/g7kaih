<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class OrangtuaAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('orangtua_id')) {
            return redirect()->route('siswa.login')
                ->withErrors(['error' => 'Silakan login terlebih dahulu sebagai orangtua.']);
        }

        return $next($request);
    }
}