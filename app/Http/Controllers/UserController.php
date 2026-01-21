<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\UserCreatedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'peran' => 'required|in:guru_wali,admin',
            'is_active' => 'required|in:0,1',
        ]);

        try {
            DB::beginTransaction();

            $defaultPassword = '12345678';

            $user = User::create([
                'email' => $validated['email'],
                'username' => $validated['username'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'peran' => $validated['peran'],
                'is_active' => $validated['is_active'] == '1',
                'password' => Hash::make($defaultPassword),
            ]);

            Mail::to($user->email)->send(new UserCreatedMail($user, $defaultPassword));

            DB::commit();

            return redirect()
                ->route('users.index')
                ->with('success', 'Pengguna berhasil ditambahkan! Email notifikasi telah dikirim ke ' . $user->email);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error creating user: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pengguna. Error: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'peran' => 'required|in:guru_wali,admin',
            'is_active' => 'required|in:0,1',
            'password' => 'nullable|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'email' => $validated['email'],
                'username' => $validated['username'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'peran' => $validated['peran'],
                'is_active' => $validated['is_active'] == '1',
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            DB::commit();

            return redirect()
                ->route('users.index')
                ->with('success', 'User berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error updating user: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui user. Error: ' . $e->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            
            return redirect()
                ->route('users.index')
                ->with('success', 'User berhasil dihapus!');
                
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Gagal menghapus user. Error: ' . $e->getMessage());
        }
    }

    public function resendCredentials(User $user)
    {
        try {
            $newPassword = '12345678';
            
            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            Mail::to($user->email)->send(new UserCreatedMail($user, $newPassword));

            return redirect()
                ->route('users.index')
                ->with('success', 'Password telah direset dan email kredensial telah dikirim ulang ke ' . $user->email);

        } catch (\Exception $e) {
            \Log::error('Error resending credentials: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Gagal mengirim ulang kredensial. Error: ' . $e->getMessage());
        }
    }
}