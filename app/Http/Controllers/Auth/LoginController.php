<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle login attempt and validate user role
     */
    protected function authenticated(Request $request, $user)
    {
        // Cek apakah yang login adalah siswa
        if ($user->peran === 'siswa') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Siswa tidak dapat login di sini. Silakan gunakan halaman login siswa.',
            ]);
        }

        // Hanya izinkan admin dan guru_wali
        if (!in_array($user->peran, ['admin', 'guru_wali'])) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Anda tidak memiliki akses ke halaman ini.',
            ]);
        }
    }

    /**
     * Where to redirect users after login.
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user->peran === 'admin') {
            return route('admin.dashboard');
        }

        if ($user->peran === 'guru_wali') {
            return route('guruwali.dashboard');
        }

        // Default fallback
        return '/dashboard';
    }
}