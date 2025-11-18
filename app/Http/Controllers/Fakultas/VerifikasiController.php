<?php

namespace App\Http\Controllers\Fakultas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MataKuliah;
use App\Models\Departemen;

class VerifikasiController extends Controller
{
    /**
     * Menampilkan daftar mata kuliah yang perlu diverifikasi.
     */
    public function index(Request $request)
    {
        $fakultasId = Auth::user()->id_fakultas;

        // 1. Ambil semua departemen di fakultas ini (untuk filter)
        $departemenList = Departemen::where('id_fakultas', $fakultasId)
                            ->orderBy('Nama_departemen')
                            ->get();

        // 2. Query dasar: Matkul di fakultas ini
        $query = MataKuliah::whereHas('departemen', function ($q) use ($fakultasId) {
            $q->where('id_fakultas', $fakultasId);
        });

        // 3. Terapkan filter jika ada
        if ($request->filled('id_departemen')) {
            $query->where('id_departemen', $request->id_departemen);
        }
        if ($request->filled('semester_mk')) {
            $query->where('Semester_mk', $request->semester_mk);
        }

        // 4. Ambil data
        $mataKuliahList = $query->with('dosen', 'departemen')
                               ->orderBy('verified', 'asc') // Tampilkan 'unverified' dulu
                               ->orderBy('Nama_mk', 'asc')
                               ->get();

        return view('fakultas.verifikasi.index', [
            'departemenList' => $departemenList,
            'mataKuliahList' => $mataKuliahList,
            'old_input' => $request->all()
        ]);
    }

    public function show(MataKuliah $matakuliah)
    {
        // 1. Security Check: Pastikan matkul ini ada di fakultas si admin
        if ($matakuliah->departemen->id_fakultas !== Auth::user()->id_fakultas) {
            return redirect()->route('fakultas.verifikasi.index')
                             ->withErrors('Anda tidak berhak melihat mata kuliah ini.');
        }

        // 2. Ambil semua relasi yang diperlukan
        $matakuliah->load('departemen.fakultas', 'komponenPenilaian', 'dokumenPendukung');

        // 3. Logika Pengecekan Tombol Verifikasi
        $komponenAda = $matakuliah->komponenPenilaian->isNotEmpty();
        $dokumenAda = $matakuliah->dokumenPendukung->isNotEmpty();
        
        // Tombol hanya aktif jika KEDUANYA sudah terisi
        $tombolVerifikasiAktif = $komponenAda && $dokumenAda;

        // 4. Kirim data ke view
        return view('fakultas.verifikasi.detail', [
            'matakuliah' => $matakuliah,
            'tombolVerifikasiAktif' => $tombolVerifikasiAktif
        ]);
    }
    /**
     * Aksi untuk memverifikasi satu mata kuliah.
     */
    public function verify(MataKuliah $matakuliah)
    {
        // Security Check: Pastikan matkul ini ada di fakultas si admin
        if ($matakuliah->departemen->id_fakultas !== Auth::user()->id_fakultas) {
            return back()->withErrors('Anda tidak berhak memverifikasi mata kuliah ini.');
        }

        // Cek ulang (opsional tapi aman)
        if ($matakuliah->komponenPenilaian->isEmpty() || $matakuliah->dokumenPendukung->isEmpty()) {
             return back()->withErrors('Gagal verifikasi: Komponen atau Dokumen Pendukung masih kosong.');
        }

        $matakuliah->verified = 'verified';
        $matakuliah->save();

        // Redirect ke halaman index (list)
        return redirect()->route('fakultas.verifikasi.index')
                         ->with('success', $matakuliah->Nama_mk . ' berhasil diverifikasi.');
    }
}