<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MataKuliah;
use App\Models\KomponenPenilaian; // Diperlukan untuk reset
use App\Models\DokumenPendukung;  // <-- TAMBAHKAN 'USE' INI
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // <-- TAMBAHKAN 'USE' INI

class MataKuliahSayaController extends Controller
{
    /**
     * Menampilkan daftar mata kuliah yang diampu oleh dosen yang login.
     */
    public function index()
    {
        // 1. Dapatkan ID user (dosen) yang sedang login
        $dosenId = Auth::id();

        // 2. Ambil mata kuliah dari database
        $mataKuliahDosen = MataKuliah::where('user_id', $dosenId)
                            ->orderBy('Semester_mk', 'asc') // Urutkan berdasarkan semester
                            ->get();

        // 3. Kirim data mata kuliah ke view
        return view('dosen.mata_kuliah_saya', [
            'daftarMataKuliah' => $mataKuliahDosen
        ]);
    }

    public function show(MataKuliah $matakuliah)
    {
        // 1. Security Check: Pastikan dosen ini adalah pemilik matkul
        if ($matakuliah->user_id !== Auth::id()) {
            return redirect()->route('dosen.matkul_saya')
                             ->withErrors('Anda tidak berhak melihat detail mata kuliah ini.');
        }

        // 2. Ambil matkul dan semua relasinya (Eager Load)
        // Kita butuh relasi departemen, lalu relasi fakultas dari departemen itu
        $matakuliah->load('departemen.fakultas', 'komponenPenilaian', 'dokumenPendukung');

        // 3. Kirim data ke view baru
        return view('dosen.mata_kuliah_detail', [
            'matakuliah' => $matakuliah
        ]);
    }

    /**
     * === METHOD RESET YANG DIPERBARUI ===
     *
     * Menghapus klaim dosen (user_id -> null),
     * menghapus metode (Metode -> null),
     * menghapus semua komponen penilaian,
     * DAN menghapus semua dokumen pendukung (file & database record).
     */
    public function reset(MataKuliah $matakuliah)
    {
        // 1. Security Check: Pastikan dosen yang login adalah pemilik matkul ini
        if ($matakuliah->user_id !== Auth::id()) {
            return back()->withErrors('Anda tidak berhak mereset mata kuliah ini.');
        }

        try {
            // Gunakan transaksi database untuk keamanan
            DB::beginTransaction();

            // --- LOGIKA BARU HAPUS DOKUMEN ---
            // 2. Ambil semua dokumen terkait mata kuliah ini
            $dokumenList = $matakuliah->dokumenPendukung; // Menggunakan relasi

            if ($dokumenList->isNotEmpty()) {
                // 3. Hapus file fisik dari storage
                foreach ($dokumenList as $doc) {
                    // $doc->path_file berisi "public/dokumen_mk/file.pdf"
                    // Storage::delete() akan menghapus file dari storage/app/public/dokumen_mk/file.pdf
                    Storage::delete($doc->path_file);
                }
                
                // 4. Hapus record dari database 'dokumen_pendukungs'
                $matakuliah->dokumenPendukung()->delete();
            }
            // --- AKHIR LOGIKA BARU ---

            // 5. Hapus semua komponen penilaian (Logika lama)
            KomponenPenilaian::where('kode_mk', $matakuliah->Kode_mk)->delete();

            // 6. Reset (un-claim) mata kuliah (Logika lama)
            $matakuliah->user_id = null;
            $matakuliah->Metode = null;

            $matakuliah->verified = 'unverified';
            
            $matakuliah->save();

            // 7. Simpan perubahan
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // Jika gagal, kirim pesan error
            return back()->withErrors('Gagal mereset mata kuliah: ' . $e->getMessage());
        }

        // 8. Redirect kembali ke halaman "Mata Kuliah Saya" dengan pesan sukses
        return redirect()->route('dosen.matkul_saya')
                         ->with('success', 'Mata kuliah "' . $matakuliah->Nama_mk . '" berhasil direset dan dilepaskan. Semua komponen dan dokumen telah dihapus.');
    }
}