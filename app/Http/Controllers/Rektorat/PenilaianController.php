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
        $departemen = collect(); // Kosong awal

        // 2. Query Dasar Mata Kuliah
        $query = MataKuliah::query()->with(['dosen', 'departemen.fakultas']);

        // 3. Logika Filter
        if ($request->filled('id_fakultas')) {
            // Ambil departemen sesuai fakultas yg dipilih
            $departemen = Departemen::where('id_fakultas', $request->id_fakultas)->get();
            
            // Filter query matkul berdasarkan fakultas (via relasi departemen)
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

        // 4. Ambil Data (Paginate agar tidak berat jika data banyak)
        $mataKuliahList = $query->orderBy('Nama_mk')->paginate(10);

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
        // Loop input nilai yang dikirim dari form tabel
        $nilaiInput = $request->input('nilai'); // Array [krs_id][komponen_id] => nilai

        if ($nilaiInput) {
            foreach ($nilaiInput as $krsId => $komponenNilai) {
                foreach ($komponenNilai as $komponenId => $nilaiAngka) {
                    // Update atau Create nilai
                    Nilai::updateOrCreate(
                        [
                            'krs_id' => $krsId,
                            'komponen_id' => $komponenId
                        ],
                        [
                            'nilai_angka' => $nilaiAngka ?? 0
                        ]
                    );
                }

                $krs = Krs::with('nilai.komponen')->find($krsId);
            // Hitung Total berdasarkan Persentase
                $totalNilai = 0;
                foreach ($matakuliah->komponenPenilaian as $komponen) {
                    // Cari nilai mahasiswa untuk komponen ini
                    // Kita pakai 'first()' dari collection relasi yang sudah di-load
                    $nilaiAda = $krs->nilai->where('komponen_id', $komponen->id)->first();
                    $angka = $nilaiAda ? $nilaiAda->nilai_angka : 0;
                    
                    // Rumus: (Nilai * Persentase) / 100
                    $totalNilai += ($angka * $komponen->persentase / 100);
                }

                // Konversi ke Huruf Mutu (Helper Function di bawah)
                $hurufMutu = $this->konversiHuruf($totalNilai);

                // Simpan ke tabel KRS
                $krs->nilai_akhir = $totalNilai;
                $krs->nilai_huruf = $hurufMutu;
                $krs->save();
            }
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
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