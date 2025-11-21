<?php

namespace App\Http\Controllers\Rektorat;

use App\Http\Controllers\Controller;
use App\Models\User;     
use App\Models\Fakultas; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ManajemenFakultasController extends Controller
{
    public function index()
    {
        $adminList = User::where('role', 'fakultas')
                        ->with('fakultas')
                        ->orderBy('name')
                        ->get();

        return view('rektorat.manajemen_fakultas.index', [
            'adminList' => $adminList
        ]);
    }

    public function create()
    {
        $fakultas = Fakultas::orderBy('Nama_fakultas')->get();
        return view('rektorat.manajemen_fakultas.create', [
            'fakultas' => $fakultas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'id_fakultas' => ['required', 'integer', 'exists:fakultas,id_fakultas'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'fakultas', 
            'id_fakultas' => $request->id_fakultas, 
        ]);

        return redirect()->route('rektorat.manajemen-fakultas.index')
                         ->with('success', 'Akun Admin Fakultas baru berhasil ditambahkan.');
    }

    // --- PERBAIKAN NAMA VARIABEL DI BAWAH INI (JADI $user) ---

    public function show(User $user)
    {
        return redirect()->route('rektorat.manajemen-fakultas.edit', $user->id);
    }

    public function edit(User $user) // <-- Sekarang cocok dengan route {user}
    {
        // Security Check
        if ($user->role !== 'fakultas') {
            return redirect()->route('rektorat.manajemen-fakultas.index')
                             ->withErrors('Akun ini bukan Admin Fakultas.');
        }

        $fakultas = Fakultas::orderBy('Nama_fakultas')->get();

        return view('rektorat.manajemen_fakultas.edit', [
            'admin' => $user, // Kirim ke view tetap dengan nama $admin
            'fakultas' => $fakultas
        ]);
    }

    public function update(Request $request, User $user) // <-- Nama variabel diperbaiki
    {
        // Security Check
        if ($user->role !== 'fakultas') {
             return back()->withErrors('Anda tidak berhak mengedit akun ini.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Perbaikan validasi unique ID
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id.',id'],
            'id_fakultas' => ['required', 'integer', 'exists:fakultas,id_fakultas'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->id_fakultas = $request->id_fakultas;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('rektorat.manajemen-fakultas.index')
                         ->with('success', 'Akun Admin Fakultas berhasil diperbarui.');
    }

    public function destroy(User $user) // <-- Nama variabel diperbaiki
    {
        // Security Check
        if ($user->role !== 'fakultas') {
             return back()->withErrors('Anda tidak berhak menghapus akun ini.');
        }

        $user->delete();

        return redirect()->route('rektorat.manajemen-fakultas.index')
                         ->with('success', 'Akun Admin Fakultas berhasil dihapus.');
    }
}