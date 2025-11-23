<?php

namespace App\Http\Controllers\Rektorat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\Krs;
use App\Models\Nilai;
use App\Models\KomponenPenilaian;
use App\Models\Fakultas;
use App\Models\Departemen;

class PenilaianController extends Controller
{
    public function list(Request $request)
    {
        // 1. Siapkan Data Filter
        $fakultas = Fakultas::orderBy('Nama_fakultas')->get();
        $departemen = collect(); 

        // 2. Query Dasar
        $query = MataKuliah::query()->with(['dosen', 'departemen.fakultas']);

        // 3. Logika Filter (Sama seperti sebelumnya)
        if ($request->filled('id_fakultas')) {
            $departemen = Departemen::where('id_fakultas', $request->id_fakultas)->get();
            $query->whereHas('departemen', function($q) use ($request) {
                $q->where('id_fakultas', $request->id_fakultas);
            });
        }
        if ($request->filled('id_departemen')) {
            $query->where('id_departemen', $request->id_departemen);
        }
        if ($request->filled('semester_mk')) {
            $query->where('Semester_mk', $request->semester_mk);
        }

        // 4. AMBIL DATA (DENGAN URUTAN BARU)
        $mataKuliahList = $query
            ->orderBy('verified', 'desc') // 'verified' (v) akan muncul sebelum 'unverified' (u) jika DESC
            ->orderBy('Nama_mk', 'asc')   // Lalu urutkan berdasarkan nama (A-Z)
            ->paginate(10);

        return view('rektorat.penilaian.list', [
            'fakultas' => $fakultas,
            'departemen' => $departemen,
            'mataKuliahList' => $mataKuliahList,
            'old_input' => $request->all()
        ]);
    }
    // Halaman detail penilaian per mata kuliah
    public function index(MataKuliah $matakuliah)
    {
        if ($matakuliah->verified !== 'verified') {
            return redirect()->route('rektorat.penilaian.list')
                             ->withErrors('Mata kuliah "' . $matakuliah->Nama_mk . '" belum diverifikasi oleh Fakultas. Penilaian belum bisa dilakukan.');
        }
        // Ambil komponen penilaian matkul ini
        $komponen = $matakuliah->komponenPenilaian;

        // Ambil mahasiswa yang mengambil matkul ini (lewat tabel KRS)
        // Kita eager load 'nilai' agar query efisien
        $mahasiswaList = Krs::where('kode_mk', $matakuliah->Kode_mk)
            ->with(['mahasiswa', 'nilai'])
            ->get();

        return view('rektorat.penilaian.index', [
            'matakuliah' => $matakuliah,
            'komponen' => $komponen,
            'mahasiswaList' => $mahasiswaList
        ]);
    }

    // Menambahkan Mahasiswa ke Mata Kuliah (Enrollment)
    public function storeMahasiswa(Request $request, MataKuliah $matakuliah)
    {
        $request->validate([
            'nim' => 'required|string',
            'nama' => 'required|string',
        ]);

        // 1. Cek atau Buat data Mahasiswa di tabel master
        $mahasiswa = Mahasiswa::firstOrCreate(
            ['nim' => $request->nim],
            ['nama' => $request->nama]
        );

        // 2. Masukkan ke KRS (Hubungkan Mahasiswa dengan Matkul)
        Krs::firstOrCreate([
            'mahasiswa_id' => $mahasiswa->id,
            'kode_mk' => $matakuliah->Kode_mk
        ]);

        return back()->with('success', 'Mahasiswa berhasil ditambahkan ke kelas.');
    }

    // Menyimpan Nilai
    public function storeNilai(Request $request, MataKuliah $matakuliah)
    {
        $nilaiAkhirInput = $request->input('nilai_akhir'); 

        if ($nilaiAkhirInput) {
            foreach ($nilaiAkhirInput as $krsId => $nilaiTotal) {
                
                // Pastikan angka valid (0-100)
                $nilaiTotal = max(0, min(100, (float) $nilaiTotal));

                // 1. Hitung Huruf Mutu
                $hurufMutu = $this->konversiHuruf($nilaiTotal);
                
                // 2. Simpan ke tabel KRS (Nilai Akhir & Huruf)
                $krs = Krs::find($krsId);
                if ($krs) {
                    $krs->nilai_akhir = $nilaiTotal;
                    $krs->nilai_huruf = $hurufMutu;
                    $krs->save();

                    // 3. DISTRIBUSI KE TABEL NILAI (LOGIKA BARU)
                    // Nilai yang disimpan adalah HASIL PERKALIAN PERSENTASE
                    // Contoh: Nilai Akhir 80, Proyek 50% -> Simpan 40.
                    foreach ($matakuliah->komponenPenilaian as $komponen) {
                        
                        // Rumus: Nilai Akhir * (Persentase / 100)
                        $nilaiBobot = $nilaiTotal * ($komponen->persentase / 100);

                        Nilai::updateOrCreate(
                            ['krs_id' => $krsId, 'komponen_id' => $komponen->id],
                            ['nilai_angka' => $nilaiBobot] // Simpan nilai hasil bobot
                        );
                    }
                }
            }
        }

        return back()->with('success', 'Nilai akhir berhasil disimpan. Poin komponen telah dihitung sesuai persentase.');
    }

    private function konversiHuruf($nilai)
    {
        if ($nilai >= 85) return 'A';
        if ($nilai >= 80) return 'A-';
        if ($nilai >= 75) return 'B+';
        if ($nilai >= 70) return 'B';
        if ($nilai >= 65) return 'B-';
        if ($nilai >= 60) return 'C+';
        if ($nilai >= 55) return 'C';
        if ($nilai >= 40) return 'D';
        return 'E';
    }
}
