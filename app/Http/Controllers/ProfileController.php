<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman edit profil (data read-only & ganti password).
     */
    public function edit()
    {
        /** @var \App\Models\User $user */ // <-- TAMBAHAN PHPDOC DI SINI
        $user = Auth::user();
        
        // Eror 'load' akan hilang
        if ($user->role == 'fakultas') {
            $user->load('fakultas');
        }
        
        return view('profile.edit', ['user' => $user]);
    }

    /**
     * Memperbarui password user.
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\User $user */ // <-- TAMBAHAN PHPDOC DI SINI
        $user = Auth::user();

        // 1. Validasi
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
        ]);

        // 2. Cek apakah password saat ini benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini yang Anda masukan salah.']);
        }

        // 3. Update password baru
        $user->password = Hash::make($request->password);
        
        // Eror 'save' akan hilang
        $user->save();

        return back()->with('success', 'Password Anda berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman "Tentang Aplikasi".
     */
    public function tentang()
    {
        return view('profile.tentang');
    }
}