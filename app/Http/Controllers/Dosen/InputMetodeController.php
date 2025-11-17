<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Departemen;
use App\Models\MataKuliah;
use App\Models\KomponenPenilaian;
use App\Models\DokumenPendukung;  // Diperlukan
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- PASTIKAN 'USE' INI ADA

class InputMetodeController extends Controller
{
    /**
     * Menampilkan halaman filter dan hasil pencarian mata kuliah.
     */
    public function index(Request $request)
    {
        $fakultas = Fakultas::orderBy('Nama_fakultas')->get();
        $departemen = collect();
        $mataKuliahList = collect();

        if ($request->filled('id_departemen') && $request->filled('semester_mk')) {
            $departemen = Departemen::where('id_fakultas', $request->id_fakultas)
                            ->orderBy('Nama_departemen')
                            ->get();
            
            $mataKuliahList = MataKuliah::where('id_departemen', $request->id_departemen)
                ->where('Semester_mk', $request->semester_mk)
                ->with('dosen') 
                ->orderBy('Nama_mk')
                ->get();
        }

        return view('dosen.input_metode.index', [
            'fakultas' => $fakultas,
            'departemen' => $departemen,
            'mataKuliahList' => $mataKuliahList,
            'old_input' => $request->all()
        ]);
    }

    /**
     * Endpoint API internal untuk dropdown dinamis.
     */
    public function getDepartemenByFakultas(Request $request)
    {
        $request->validate(['id_fakultas' => 'required|integer|exists:fakultas,id_fakultas']);
        $departemen = Departemen::where('id_fakultas', $request->id_fakultas)
            ->orderBy('Nama_departemen')
            ->get();
        return response()->json($departemen);
    }

    /**
     * Menampilkan halaman form untuk edit metode dan komponen penilaian.
     */
    public function edit(MataKuliah $matakuliah)
    {
        // Security Check
        if (!is_null($matakuliah->user_id) && $matakuliah->user_id != Auth::id()) {
            return redirect()->route('dosen.input_metode.index')
                             ->withErrors('Mata kuliah "' . $matakuliah->Nama_mk . '" sudah diampu oleh dosen lain.');
        }

        // Ambil data matkul DAN relasi komponen & dokumen
        $matakuliah->load('komponenPenilaian', 'dokumenPendukung');
        
        $komponen = $matakuliah->komponenPenilaian;

        if ($komponen->isEmpty()) {
            $komponen = collect([
                new KomponenPenilaian(['nama_komponen' => 'Partisipasi', 'persentase' => 10]),
                new KomponenPenilaian(['nama_komponen' => 'Proyek', 'persentase' => 20]),
                new KomponenPenilaian(['nama_komponen' => 'Kuis', 'persentase' => 10]),
                new KomponenPenilaian(['nama_komponen' => 'Tugas', 'persentase' => 20]),
                new KomponenPenilaian(['nama_komponen' => 'UTS', 'persentase' => 20]),
                new KomponenPenilaian(['nama_komponen' => 'UAS', 'persentase' => 20]),
            ]);
        }

        return view('dosen.input_metode.edit', [
            'matakuliah' => $matakuliah,
            'komponen' => $komponen
        ]);
    }

    /**
     * Menyimpan (update) metode, komponen, dan dokumen.
     */
    public function update(Request $request, MataKuliah $matakuliah)
    {
        // Security Check
        if (!is_null($matakuliah->user_id) && $matakuliah->user_id != Auth::id()) {
             return back()->withInput()->withErrors(['auth' => 'Anda tidak berhak mengubah mata kuliah ini.']);
        }

        // === VALIDASI LOGIKA BISNIS ===
        
        $request->validate([
            'metode' => 'required|string|in:Biasa,PjBL,CBM',
            'komponen' => 'required|array|min:1',
            'komponen.*.nama' => 'required|string|max:255',
            'komponen.*.persen' => 'required|integer|min:0|max:100',
            'dokumen_pendukung' => 'nullable|array|size:10', // Jika diisi, harus 10
            'dokumen_pendukung.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:5120', 
        ], [
            'dokumen_pendukung.size' => 'Jika Anda meng-upload dokumen, jumlahnya harus tepat 10 file.'
        ]);

        // Validasi 100% dan PjBL
        $komponenList = $request->input('komponen');
        $totalPersen = 0;
        $persenProyek = 0;
        foreach ($komponenList as $komponen) {
            $totalPersen += (int) $komponen['persen'];
            if (strtolower($komponen['nama']) == 'proyek') {
                $persenProyek = (int) $komponen['persen'];
            }
        }
        if ($totalPersen != 100) {
            return back()->withInput()->withErrors(['komponen' => 'Total persentase komponen harus 100%. Saat ini: ' . $totalPersen . '%']);
        }
        $metode = $request->input('metode');
        if (($metode == 'PjBL' || $metode == 'CBM') && $persenProyek < 50) {
            return back()->withInput()->withErrors(['komponen' => 'Untuk metode PjBL atau CBM, persentase komponen "Proyek" harus >= 50%. Saat ini baru ' . $persenProyek . '%.']);
        }

        // === SIMPAN DATA KE DATABASE (Transaksi) ===
        try {
            DB::beginTransaction();

            // 1. Logika Klaim
            if (is_null($matakuliah->user_id)) {
                $matakuliah->user_id = Auth::id(); 
            }
            
            // 2. Update metode
            $matakuliah->Metode = $metode;
            $matakuliah->save();

            // 3. Update Komponen
            KomponenPenilaian::where('kode_mk', $matakuliah->Kode_mk)->delete();
            foreach ($komponenList as $komponen) {
                KomponenPenilaian::create([
                    'kode_mk' => $matakuliah->Kode_mk,
                    'nama_komponen' => $komponen['nama'],
                    'persentase' => (int) $komponen['persen'],
                ]);
            }

            // --- 4. LOGIKA UPLOAD FILE (DENGAN PERBAIKAN) ---
            if ($request->hasFile('dokumen_pendukung')) {
                
                // --- A. HAPUS SEMUA DOKUMEN LAMA ---
                
                // 1. Ambil semua dokumen lama dari database
                $dokumenLama = $matakuliah->dokumenPendukung;

                if ($dokumenLama->isNotEmpty()) {
                    // 2. Hapus file fisik lama dari storage
                    foreach ($dokumenLama as $doc) {
                        Storage::delete($doc->path_file);
                    }
                    
                    // 3. Hapus record lama dari database
                    $matakuliah->dokumenPendukung()->delete();
                }

                // --- B. SIMPAN 10 DOKUMEN BARU ---
                foreach ($request->file('dokumen_pendukung') as $file) {
                    $namaFileAsli = $file->getClientOriginalName();
                    // Simpan file ke storage/app/public/dokumen_mk
                    $path = $file->store('public/dokumen_mk'); 

                    // Simpan record baru ke database
                    DokumenPendukung::create([
                        'kode_mk' => $matakuliah->Kode_mk,
                        'nama_file' => $namaFileAsli,
                        'path_file' => $path 
                    ]);
                }
            }
            // --- AKHIR LOGIKA UPLOAD ---

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['db' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('dosen.input_metode.index')
                         ->with('success', 'Metode pembelajaran dan komponen untuk ' . $matakuliah->Nama_mk . ' berhasil disimpan.');
    }
}