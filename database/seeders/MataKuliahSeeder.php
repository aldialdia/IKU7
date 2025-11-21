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
        $dosen1 = User::where('email', 'Dosen1@gmail.com')->first();

        // ambil beberapa departemen yang kita buat di FakultasDepartemenSeeder
        $departemenTI = Departemen::where('Nama_departemen', 'Teknik Informatika')->first();
        $departemenSI = Departemen::where('Nama_departemen', 'Sistem Informasi')->first();
        $departemenTM = Departemen::where('Nama_departemen', 'Teknik Mesin')->first();
        $departemenTS = Departemen::where('Nama_departemen', 'Teknik Sipil')->first();
        $departemenMAN = Departemen::where('Nama_departemen', 'Manajemen')->first();
        $departemenAK = Departemen::where('Nama_departemen', 'Akuntansi')->first();
        $departemenMT = Departemen::where('Nama_departemen', 'Matematika')->first();
        $departemenFM = Departemen::where('Nama_departemen', 'Fisika')->first();
        $departemenAGR = Departemen::where('Nama_departemen', 'Agroteknologi')->first();
        $departemenPM = Departemen::where('Nama_departemen', 'Pendidikan Matematika')->first();
        $departemenHJ = Departemen::where('Nama_departemen', 'Ilmu Hukum')->first();
        $departemenKED = Departemen::where('Nama_departemen', 'Pendidikan Dokter')->first();
        $departemenIK = Departemen::where('Nama_departemen', 'Ilmu Komunikasi')->first();

        // 3. Buat Mata Kuliah contoh (biasanya user_id = null artinya belum diklaim)
        $created = [];

        if ($departemenTI) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'IF101',
                'user_id' => $dosen1 ? $dosen1->id : null,
                'id_departemen' => $departemenTI->id_departemen,
                'Nama_mk' => 'Algoritma dan Pemrograman',
                'Semester_mk' => 1,
                'SKS' => 4,
                'Metode' => 'Tatap Muka'
            ]);

            $created[] = MataKuliah::create([
                'Kode_mk' => 'IF201',
                'user_id' => null,
                'id_departemen' => $departemenTI->id_departemen,
                'Nama_mk' => 'Struktur Data',
                'Semester_mk' => 3,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

        if ($departemenSI) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'SI301',
                'user_id' => null,
                'id_departemen' => $departemenSI->id_departemen,
                'Nama_mk' => 'Analisis dan Perancangan Sistem',
                'Semester_mk' => 5,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

        if ($departemenTM) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'TM101',
                'user_id' => null,
                'id_departemen' => $departemenTM->id_departemen,
                'Nama_mk' => 'Teknik Material',
                'Semester_mk' => 3,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

        if ($departemenTS) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'TS102',
                'user_id' => null,
                'id_departemen' => $departemenTS->id_departemen,
                'Nama_mk' => 'Mekanika Tanah',
                'Semester_mk' => 5,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

        if ($departemenMAN) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'MN101',
                'user_id' => null,
                'id_departemen' => $departemenMAN->id_departemen,
                'Nama_mk' => 'Manajemen Keuangan',
                'Semester_mk' => 4,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

        if ($departemenAK) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'AK101',
                'user_id' => null,
                'id_departemen' => $departemenAK->id_departemen,
                'Nama_mk' => 'Akuntansi Dasar',
                'Semester_mk' => 2,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

        if ($departemenMT) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'MT101',
                'user_id' => null,
                'id_departemen' => $departemenMT->id_departemen,
                'Nama_mk' => 'Calculus I',
                'Semester_mk' => 1,
                'SKS' => 4,
                'Metode' => null
            ]);
        }

        if ($departemenFM) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'FM101',
                'user_id' => null,
                'id_departemen' => $departemenFM->id_departemen,
                'Nama_mk' => 'Fisika Dasar',
                'Semester_mk' => 1,
                'SKS' => 4,
                'Metode' => null
            ]);
        }

        if ($departemenAGR) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'AG101',
                'user_id' => null,
                'id_departemen' => $departemenAGR->id_departemen,
                'Nama_mk' => 'Dasar-Dasar Agronomi',
                'Semester_mk' => 2,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

        if ($departemenPM) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'PM101',
                'user_id' => null,
                'id_departemen' => $departemenPM->id_departemen,
                'Nama_mk' => 'Metode Pengajaran Matematika',
                'Semester_mk' => 2,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

        if ($departemenHJ) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'HJ101',
                'user_id' => null,
                'id_departemen' => $departemenHJ->id_departemen,
                'Nama_mk' => 'Pengantar Hukum',
                'Semester_mk' => 1,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

        if ($departemenKED) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'KD101',
                'user_id' => null,
                'id_departemen' => $departemenKED->id_departemen,
                'Nama_mk' => 'Ilmu Kedokteran Dasar',
                'Semester_mk' => 1,
                'SKS' => 6,
                'Metode' => null
            ]);
        }

        if ($departemenIK) {
            $created[] = MataKuliah::create([
                'Kode_mk' => 'IK101',
                'user_id' => null,
                'id_departemen' => $departemenIK->id_departemen,
                'Nama_mk' => 'Dasar-Dasar Komunikasi',
                'Semester_mk' => 1,
                'SKS' => 3,
                'Metode' => null
            ]);
        }

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