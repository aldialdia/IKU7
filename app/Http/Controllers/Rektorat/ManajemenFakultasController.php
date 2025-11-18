<?php

namespace App\Http\Controllers\Rektorat;

use App\Http\Controllers\Controller;
use App\Models\User;     // Model User
use App\Models\Fakultas; // Model Fakultas (untuk dropdown)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ManajemenFakultasController extends Controller
{
    /**
     * Menampilkan daftar semua akun Admin Fakultas.
     */
    public function index()
    {
        // Ambil semua user yang role-nya 'fakultas'
        // Eager load relasi 'fakultas' untuk menampilkan nama fakultasnya
        $adminList = User::where('role', 'fakultas')
                        ->with('fakultas') // Menggunakan relasi fakultas() di Model User
                        ->orderBy('name')
                        ->get();

        return view('rektorat.manajemen_fakultas.index', [
            'adminList' => $adminList
        ]);
    }

    /**
     * Menampilkan form untuk membuat akun Admin Fakultas baru.
     */
    public function create()
    {
        // Ambil semua data fakultas untuk ditampilkan di dropdown
        $fakultas = Fakultas::orderBy('Nama_fakultas')->get();

        return view('rektorat.manajemen_fakultas.create', [
            'fakultas' => $fakultas
        ]);
    }

    /**
     * Menyimpan akun Admin Fakultas baru ke database.
     */
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
            'role' => 'fakultas', // Paksa role sebagai 'fakultas'
            'id_fakultas' => $request->id_fakultas, // Assign ke fakultas yang dipilih
        ]);

        return redirect()->route('rektorat.manajemen-fakultas.index')
                         ->with('success', 'Akun Admin Fakultas baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail (kita arahkan ke 'edit' agar fungsional).
     */
    public function show(User $user)
    {
        return redirect()->route('rektorat.manajemen-fakultas.edit', $user->id);
    }

    /**
     * Menampilkan form untuk mengedit akun Admin Fakultas.
     * Kita ganti nama variabel $manajemen_foulta menjadi $user agar lebih jelas
     */
    public function edit(User $manajemen_foulta) // Nama variabel harus cocok dgn parameter route
    {
        $admin = $manajemen_foulta; // Ganti nama agar mudah dibaca

        // Security Check: Pastikan yang diedit adalah akun 'fakultas'
        if ($admin->role !== 'fakultas') {
            return redirect()->route('rektorat.manajemen-fakultas.index')
                             ->withErrors('Akun ini bukan Admin Fakultas.');
        }

        $fakultas = Fakultas::orderBy('Nama_fakultas')->get();

        return view('rektorat.manajemen_fakultas.edit', [
            'admin' => $admin,
            'fakultas' => $fakultas
        ]);
    }

    /**
     * Update data akun Admin Fakultas di database.
     */
    public function update(Request $request, User $manajemen_foulta)
    {
        $admin = $manajemen_foulta;

        // Security Check
        if ($admin->role !== 'fakultas') {
             return back()->withErrors('Anda tidak berhak mengedit akun ini.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$admin->id.',id'],
            'id_fakultas' => ['required', 'integer', 'exists:fakultas,id_fakultas'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->id_fakultas = $request->id_fakultas;

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('rektorat.manajemen-fakultas.index')
                         ->with('success', 'Akun Admin Fakultas berhasil diperbarui.');
    }

    /**
     * Hapus akun Admin Fakultas.
     */
    public function destroy(User $manajemen_foulta)
    {
        $admin = $manajemen_foulta;

        // Security Check
        if ($admin->role !== 'fakultas') {
             return back()->withErrors('Anda tidak berhak menghapus akun ini.');
        }

        $admin->delete();

        return redirect()->route('rektorat.manajemen-fakultas.index')
                         ->with('success', 'Akun Admin Fakultas berhasil dihapus.');
    }
}