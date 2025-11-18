<?php

namespace App\Http\Controllers\Fakultas;

use App\Http\Controllers\Controller;
use App\Models\User; // Model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ManajemenDosenController extends Controller
{
    // Helper untuk mengambil ID Fakultas Admin
    private function getAdminFakultasId()
    {
        return Auth::user()->id_fakultas;
    }

    /**
     * Tampilkan daftar Dosen di fakultas ini.
     */
    public function index()
    {
        $dosenList = User::where('role', 'dosen')
                        ->where('id_fakultas', $this->getAdminFakultasId())
                        ->orderBy('name')
                        ->get();

        return view('fakultas.manajemen_dosen.index', [
            'dosenList' => $dosenList
        ]);
    }

    /**
     * Tampilkan form untuk membuat Dosen baru.
     */
    public function create()
    {
        return view('fakultas.manajemen_dosen.create');
    }

    /**
     * Simpan Dosen baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'dosen', // Paksa role
            'id_fakultas' => $this->getAdminFakultasId(), // Paksa ke fakultas si admin
        ]);

        return redirect()->route('fakultas.manajemen-dosen.index')
                         ->with('success', 'Akun dosen baru berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail Dosen (kita skip ini, langsung ke edit).
     */
    public function show(User $user)
    {
         // Anda bisa membuat view detail jika mau, tapi 'edit' lebih fungsional
         return redirect()->route('fakultas.manajemen-dosen.edit', $user->id);
    }

    /**
     * Tampilkan form untuk mengedit Dosen.
     * Parameter $manajemen_dosen adalah User
     */
    public function edit(User $manajemen_dosen) // Nama parameter harus cocok dengan route resource
    {
        $dosen = $manajemen_dosen;

        // Security Check
        if ($dosen->id_fakultas !== $this->getAdminFakultasId() || $dosen->role !== 'dosen') {
            return redirect()->route('fakultas.manajemen-dosen.index')
                             ->withErrors('Anda tidak berhak mengedit akun ini.');
        }

        return view('fakultas.manajemen_dosen.edit', ['dosen' => $dosen]);
    }

    /**
     * Update data Dosen di database.
     */
    public function update(Request $request, User $manajemen_dosen)
    {
        $dosen = $manajemen_dosen;

        // Security Check
        if ($dosen->id_fakultas !== $this->getAdminFakultasId() || $dosen->role !== 'dosen') {
             return back()->withErrors('Anda tidak berhak mengedit akun ini.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$dosen->id.',id'],
            // Password opsional (hanya jika diisi)
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $dosen->name = $request->name;
        $dosen->email = $request->email;

        if ($request->filled('password')) {
            $dosen->password = Hash::make($request->password);
        }

        $dosen->save();

        return redirect()->route('fakultas.manajemen-dosen.index')
                         ->with('success', 'Akun dosen berhasil diperbarui.');
    }

    /**
     * Hapus akun Dosen.
     */
    public function destroy(User $manajemen_dosen)
    {
        $dosen = $manajemen_dosen;

        // Security Check
        if ($dosen->id_fakultas !== $this->getAdminFakultasId() || $dosen->role !== 'dosen') {
             return back()->withErrors('Anda tidak berhak menghapus akun ini.');
        }

        // Logika tambahan: Cek jika dosen masih mengampu matkul
        if ($dosen->mataKuliah()->exists()) {
             return back()->withErrors('Gagal menghapus: Dosen masih terikat sebagai pengampu mata kuliah.');
             // Kita bisa paksa reset matkulnya dulu jika mau, tapi ini lebih aman.
        }

        $dosen->delete();

        return redirect()->route('fakultas.manajemen-dosen.index')
                         ->with('success', 'Akun dosen berhasil dihapus.');
    }
}