<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Siswa;
use App\Models\Orangtua;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show login form untuk user biasa (admin, guru_wali)
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Login untuk user biasa (admin, guru_wali)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan peran
            $user = Auth::user();
            
            switch ($user->peran) {
                case 'admin':
                    return redirect()->route('dashboard.admin');
                case 'guru_wali':
                    return redirect()->route('dashboard.guruwali');
                case 'orangtua':
                    return redirect()->route('dashboard.orangtua.admin');
                case 'siswa':
                    return redirect()->route('dashboard.siswa.auth');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout untuk user biasa
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show login form untuk siswa (session based)
     */
    public function showSiswaLoginForm()
    {
        return view('siswa.login');
    }

    /**
     * Login untuk siswa (session based)
     */
    public function siswaLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $siswa = Siswa::where('nis', $request->nis)->first();

        if ($siswa && Hash::check($request->password, $siswa->password)) {
            // Set session untuk siswa
            Session::put('siswa_id', $siswa->id);
            Session::put('siswa_nis', $siswa->nis);
            Session::put('siswa_nama', $siswa->nama_lengkap);

            return redirect()->route('siswa.dashboard');
        }

        return redirect()->back()
            ->withErrors(['nis' => 'NIS atau password salah.'])
            ->withInput();
    }

    /**
     * Logout untuk siswa (session based)
     */
    public function siswaLogout(Request $request)
    {
        Session::forget(['siswa_id', 'siswa_nis', 'siswa_nama']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('siswa.login');
    }

    /**
     * Show login form untuk orangtua (session based)
     */
    public function showOrangtuaLoginForm()
    {
        return view('orangtua.login');
    }

    /**
     * Login untuk orangtua (session based)
     */
    public function orangtuaLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $orangtua = Orangtua::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if ($orangtua && Hash::check($request->password, $orangtua->password)) {
            // Set session untuk orangtua
            Session::put('orangtua_id', $orangtua->id);
            Session::put('orangtua_nama', $orangtua->nama_ayah ?? $orangtua->nama_ibu);

            return redirect()->route('orangtua.dashboard');
        }

        return redirect()->back()
            ->withErrors(['username' => 'Username atau password salah.'])
            ->withInput();
    }

    /**
     * Logout untuk orangtua (session based)
     */
    public function orangtuaLogout(Request $request)
    {
        Session::forget(['orangtua_id', 'orangtua_nama']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('orangtua.login');
    }

    /**
     * Show login form untuk user biasa (redirect ke login default)
     */
    public function showUserLoginForm()
    {
        return redirect()->route('login');
    }
}