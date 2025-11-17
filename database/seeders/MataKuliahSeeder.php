<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Departemen;
use App\Models\MataKuliah;
use App\Models\KomponenPenilaian;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kosongkan tabel terkait
        // Matikan pengecekan foreign key agar truncate berjalan mulus
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); 
        MataKuliah::truncate();
        KomponenPenilaian::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Nyalakan kembali

        // 2. Ambil data Dosen & Departemen yang sudah ada dari seeder lain
        // Kita asumsikan Dosen1@gmail.com ada dari DummyUsersSeeder
        $dosen1 = User::where('email', 'Dosen1@gmail.com')->first();
        $departemenTI = Departemen::where('Nama_departemen', 'Teknik Informatika')->first();
        $departemenSI = Departemen::where('Nama_departemen', 'Sistem Informasi')->first();

        // 3. Validasi: Pastikan data Dosen & Departemen ada
        if (!$dosen1 || !$departemenTI || !$departemenSI) {
            // Tampilkan pesan error di terminal jika seeder prasyarat belum jalan
            $this->command->error('Data Dosen atau Departemen tidak ditemukan.');
            $this->command->error('Pastikan Anda sudah menjalankan: DummyUsersSeeder & FakultasDepartemenSeeder.');
            $this->command->error('Hentikan seeder MataKuliah.');
            return; // Hentikan proses seeder ini
        }

        // 4. Buat Mata Kuliah
        
        // MK1: Mata kuliah ini SUDAH DIKLAIM oleh Dosen1
        $mk1 = MataKuliah::create([
            'Kode_mk' => 'IF-101',
            'user_id' => $dosen1->id, // Langsung di-assign ke Dosen1
            'id_departemen' => $departemenTI->id_departemen,
            'Nama_mk' => 'Algoritma dan Pemrograman',
            'Semester_mk' => 1,
            'SKS' => 4,
            'Metode' => 'Biasa' // Sudah diatur
        ]);

        // MK2: Mata kuliah ini BELUM DIKLAIM
        $mk2 = MataKuliah::create([
            'Kode_mk' => 'SI-301',
            'user_id' => null, // user_id = null artinya "belum diklaim"
            'id_departemen' => $departemenSI->id_departemen,
            'Nama_mk' => 'Analisis dan Perancangan Sistem',
            'Semester_mk' => 3,
            'SKS' => 3,
            'Metode' => null // Belum diatur
        ]);
        
        // MK3: Mata kuliah ini BELUM DIKLAIM
        $mk3 = MataKuliah::create([
            'Kode_mk' => 'IF-305',
            'user_id' => null, // user_id = null artinya "belum diklaim"
            'id_departemen' => $departemenTI->id_departemen,
            'Nama_mk' => 'Proyek Perangkat Lunak',
            'Semester_mk' => 3, // Semester 3, sama dengan MK2
            'SKS' => 4,
            'Metode' => null // Belum diatur
        ]);

        // 5. Buat Komponen Penilaian (Hanya untuk MK yang sudah di-set)
        
        // Komponen untuk MK1 (Algoritma)
        KomponenPenilaian::create(['kode_mk' => $mk1->Kode_mk, 'nama_komponen' => 'Partisipasi', 'persentase' => 10]);
        KomponenPenilaian::create(['kode_mk' => $mk1->Kode_mk, 'nama_komponen' => 'Tugas', 'persentase' => 20]);
        KomponenPenilaian::create(['kode_mk' => $mk1->Kode_mk, 'nama_komponen' => 'Kuis', 'persentase' => 20]);
        KomponenPenilaian::create(['kode_mk' => $mk1->Kode_mk, 'nama_komponen' => 'UTS', 'persentase' => 25]);
        KomponenPenilaian::create(['kode_mk' => $mk1->Kode_mk, 'nama_komponen' => 'UAS', 'persentase' => 25]);

        // Untuk MK2 dan MK3, komponen akan dibuat otomatis saat dosen mengedit
        // melalui form (sesuai logic di InputMetodeController@edit)
    }
}