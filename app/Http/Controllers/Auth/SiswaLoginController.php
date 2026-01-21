<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Siswa;
use App\Models\Orangtua;
use Illuminate\Support\Facades\Hash;

class SiswaLoginController extends Controller
{
    /**
     * Tampilkan form login siswa & orangtua
     */
    public function showLoginForm()
    {
        // Jika sudah login siswa, redirect ke dashboard siswa
        if (Session::has('siswa_id')) {
            return redirect()->route('siswa.dashboard');
        }

        // Jika sudah login orangtua, redirect ke dashboard orangtua
        if (Session::has('orangtua_id')) {
            return redirect()->route('orangtua.dashboard');
        }

        return view('auth.siswa-login');
    }

    /**
     * Proses login siswa atau orangtua
     */
    public function login(Request $request)
    {
        // Validasi login type
        $request->validate([
            'login_type' => 'required|in:siswa,orangtua',
        ], [
            'login_type.required' => 'Pilih jenis login',
        ]);

        if ($request->login_type === 'siswa') {
            return $this->loginSiswa($request);
        } else {
            return $this->loginOrangtua($request);
        }
    }

    /**
     * Proses login untuk siswa
     */
    private function loginSiswa(Request $request)
    {
        // Validasi NIS
        $request->validate([
            'nis' => 'required|string',
        ], [
            'nis.required' => 'NIS harus diisi',
        ]);

        // Cari siswa berdasarkan NIS
        $siswa = Siswa::where('nis', $request->nis)->first();

        if (!$siswa) {
            return back()
                ->withErrors(['nis' => 'NIS tidak ditemukan'])
                ->withInput();
        }

        // Hapus session orangtua jika ada
        Session::forget(['orangtua_id', 'orangtua_nama', 'siswa_anak_id', 'siswa_anak_nama', 'siswa_anak_nis', 'user_type']);

        // Login sebagai siswa
        Session::put('siswa_id', $siswa->id);
        Session::put('siswa_nis', $siswa->nis);
        Session::put('siswa_nama', $siswa->nama_lengkap);
        Session::put('user_type', 'siswa');

        // Log untuk debugging
        \Log::info('Siswa login successful', [
            'siswa_id' => $siswa->id,
            'siswa_nama' => $siswa->nama_lengkap,
            'siswa_nis' => $siswa->nis,
            'session_id' => Session::getId()
        ]);

        return redirect()->route('siswa.dashboard')
            ->with('success', 'Selamat datang, ' . $siswa->nama_lengkap);
    }

    /**
     * Proses login untuk orangtua (menggunakan NIS anak)
     */
    private function loginOrangtua(Request $request)
    {
        // Validasi NIS anak
        $request->validate([
            'nis_anak' => 'required|string',
        ], [
            'nis_anak.required' => 'NIS anak harus diisi',
        ]);

        // Cari siswa berdasarkan NIS
        $siswa = Siswa::where('nis', $request->nis_anak)->first();

        if (!$siswa) {
            return back()
                ->withErrors(['nis_anak' => 'NIS anak tidak ditemukan'])
                ->withInput();
        }

        // **PENTING: Cari orangtua yang BENAR-BENAR terkait dengan siswa ini**
        $orangtua = Orangtua::where('siswa_id', $siswa->id)->first();

        if (!$orangtua) {
            return back()
                ->withErrors(['nis_anak' => 'Data orangtua belum terdaftar untuk NIS "' . $siswa->nis . '". Silakan minta siswa untuk mengisi data orangtua terlebih dahulu.'])
                ->withInput();
        }

        if (!$orangtua->is_active) {
            return back()
                ->withErrors(['nis_anak' => 'Akun orangtua tidak aktif. Silakan hubungi admin sekolah.'])
                ->withInput();
        }

        // **HAPUS SEMUA SESSION SEBELUMNYA**
        Session::flush();

        // **SET SESSION DENGAN DATA YANG SPESIFIK**
        $namaOrangtua = $orangtua->nama_ayah ?? $orangtua->nama_ibu ?? $orangtua->nama_wali ?? 'Orang Tua';
        
        Session::put('orangtua_id', $orangtua->id);
        Session::put('orangtua_nama', $namaOrangtua);
        Session::put('siswa_anak_id', $siswa->id);
        Session::put('siswa_anak_nama', $siswa->nama_lengkap);
        Session::put('siswa_anak_nis', $siswa->nis);
        Session::put('user_type', 'orangtua');
        
        // **TAMBAHKAN VERIFIKASI TOKEN**
        Session::put('ortu_token', md5($orangtua->id . '_' . $siswa->id . '_' . env('APP_KEY')));

        // **LOG UNTUK DEBUG**
        \Log::info('Orangtua login successful', [
            'orangtua_id' => $orangtua->id,
            'orangtua_nama' => $namaOrangtua,
            'siswa_id' => $siswa->id,
            'siswa_nama' => $siswa->nama_lengkap,
            'siswa_nis' => $siswa->nis,
            'session_id' => Session::getId()
        ]);

        return redirect()->route('orangtua.dashboard')
            ->with('success', 'Selamat datang, ' . $namaOrangtua . ' (Orangtua dari ' . $siswa->nama_lengkap . ')');
    }

    /**
     * Proses login siswa atau orangtua via Barcode Scanner
     */
    public function loginWithBarcode(Request $request)
    {
        $request->validate([
            'login_type' => 'required|in:siswa,orangtua',
            'nis' => 'required|string',
        ]);

        if ($request->login_type === 'siswa') {
            return $this->loginSiswaBarcode($request);
        } else {
            return $this->loginOrangtuaBarcode($request);
        }
    }

    /**
     * Login siswa via barcode
     */
    private function loginSiswaBarcode(Request $request)
    {
        $siswa = Siswa::where('nis', $request->nis)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIS tidak ditemukan'
            ], 404);
        }

        Session::forget(['orangtua_id', 'orangtua_nama', 'siswa_anak_id', 'siswa_anak_nama', 'siswa_anak_nis', 'user_type']);

        Session::put('siswa_id', $siswa->id);
        Session::put('siswa_nis', $siswa->nis);
        Session::put('siswa_nama', $siswa->nama_lengkap);
        Session::put('user_type', 'siswa');

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil sebagai siswa',
            'redirect' => route('siswa.dashboard')
        ]);
    }

    /**
     * Login orangtua via barcode
     */
    private function loginOrangtuaBarcode(Request $request)
    {
        $siswa = Siswa::where('nis', $request->nis)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'NIS tidak ditemukan'
            ], 404);
        }

        $orangtua = Orangtua::where('siswa_id', $siswa->id)->first();

        if (!$orangtua) {
            return response()->json([
                'success' => false,
                'message' => 'Data orangtua belum terdaftar untuk siswa ini'
            ], 404);
        }

        if (!$orangtua->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun orangtua tidak aktif'
            ], 403);
        }

        Session::flush();

        $namaOrangtua = $orangtua->nama_ayah ?? $orangtua->nama_ibu ?? $orangtua->nama_wali ?? 'Orang Tua';
        
        Session::put('orangtua_id', $orangtua->id);
        Session::put('orangtua_nama', $namaOrangtua);
        Session::put('siswa_anak_id', $siswa->id);
        Session::put('siswa_anak_nama', $siswa->nama_lengkap);
        Session::put('siswa_anak_nis', $siswa->nis);
        Session::put('user_type', 'orangtua');
        Session::put('ortu_token', md5($orangtua->id . '_' . $siswa->id . '_' . env('APP_KEY')));

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil sebagai orangtua',
            'redirect' => route('orangtua.dashboard')
        ]);
    }

    /**
     * Logout siswa
     */
    public function logout(Request $request)
    {
        $userType = Session::get('user_type');
        Session::flush();
        
        return redirect()->route('siswa.login')
            ->with('success', 'Anda telah berhasil logout');
    }
    
    /**
     * Logout orangtua
     */
    public function logoutOrangtua(Request $request)
    {
        Session::flush();
        
        return redirect()->route('siswa.login')
            ->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Validasi session orangtua
     */
    public static function validateOrangtuaSession($siswaId = null)
    {
        if (!Session::has('orangtua_id') || !Session::has('siswa_anak_id')) {
            return false;
        }

        $orangtuaId = Session::get('orangtua_id');
        $siswaAnakId = Session::get('siswa_anak_id');

        // Jika ada parameter siswaId, validasi sesuai
        if ($siswaId && $siswaAnakId != $siswaId) {
            return false;
        }

        // Validasi di database
        $orangtua = Orangtua::where('id', $orangtuaId)
                           ->where('siswa_id', $siswaAnakId)
                           ->first();

        return $orangtua ? true : false;
    }
}