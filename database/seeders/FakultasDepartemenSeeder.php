<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Fakultas;
use App\Models\Departemen;
use Illuminate\Support\Facades\DB;

class FakultasDepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel dulu (opsional, tapi bagus untuk testing)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Departemen::truncate();
        Fakultas::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Buat Fakultas (representatif, mirip struktur Universitas Andalas)
        $f_ilkom = Fakultas::create(['Nama_fakultas' => 'Fakultas Teknologi Informasi']);
        $f_tek = Fakultas::create(['Nama_fakultas' => 'Fakultas Teknik']);
        $f_ekon = Fakultas::create(['Nama_fakultas' => 'Fakultas Ekonomi dan Bisnis']);
        $f_hukum = Fakultas::create(['Nama_fakultas' => 'Fakultas Hukum']);
        $f_pertanian = Fakultas::create(['Nama_fakultas' => 'Fakultas Pertanian']);
        $f_fkip = Fakultas::create(['Nama_fakultas' => 'Fakultas Keguruan dan Ilmu Pendidikan']);
        $f_fmipa = Fakultas::create(['Nama_fakultas' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam']);
        $f_fisip = Fakultas::create(['Nama_fakultas' => 'Fakultas Ilmu Sosial dan Ilmu Politik']);
        $f_kedokteran = Fakultas::create(['Nama_fakultas' => 'Fakultas Kedokteran']);

        // 2. Buat Departemen / Program studi untuk masing-masing fakultas (contoh umum)
        // Fakultas Ilmu Komputer
        $ti = Departemen::create(['id_fakultas' => $f_ilkom->id_fakultas, 'Nama_departemen' => 'Teknik Informatika']);
        $si = Departemen::create(['id_fakultas' => $f_ilkom->id_fakultas, 'Nama_departemen' => 'Sistem Informasi']);

        // Fakultas Teknik
        $tm = Departemen::create(['id_fakultas' => $f_tek->id_fakultas, 'Nama_departemen' => 'Teknik Mesin']);
        $ts = Departemen::create(['id_fakultas' => $f_tek->id_fakultas, 'Nama_departemen' => 'Teknik Sipil']);
        $tel = Departemen::create(['id_fakultas' => $f_tek->id_fakultas, 'Nama_departemen' => 'Teknik Elektro']);

        // Fakultas Ekonomi dan Bisnis
        $man = Departemen::create(['id_fakultas' => $f_ekon->id_fakultas, 'Nama_departemen' => 'Manajemen']);
        $ak = Departemen::create(['id_fakultas' => $f_ekon->id_fakultas, 'Nama_departemen' => 'Akuntansi']);

        // Fakultas Hukum
        $hj = Departemen::create(['id_fakultas' => $f_hukum->id_fakultas, 'Nama_departemen' => 'Ilmu Hukum']);

        // Fakultas Pertanian
        $agr = Departemen::create(['id_fakultas' => $f_pertanian->id_fakultas, 'Nama_departemen' => 'Agroteknologi']);

        // FKIP
        $p_mtk = Departemen::create(['id_fakultas' => $f_fkip->id_fakultas, 'Nama_departemen' => 'Pendidikan Matematika']);
        $p_bhs = Departemen::create(['id_fakultas' => $f_fkip->id_fakultas, 'Nama_departemen' => 'Pendidikan Bahasa dan Sastra']);

        // FMIPA
        $mt = Departemen::create(['id_fakultas' => $f_fmipa->id_fakultas, 'Nama_departemen' => 'Matematika']);
        $fm = Departemen::create(['id_fakultas' => $f_fmipa->id_fakultas, 'Nama_departemen' => 'Fisika']);
        $ch = Departemen::create(['id_fakultas' => $f_fmipa->id_fakultas, 'Nama_departemen' => 'Kimia']);

        // FISIP
        $ilkom = Departemen::create(['id_fakultas' => $f_fisip->id_fakultas, 'Nama_departemen' => 'Ilmu Komunikasi']);

        // Kedokteran
        $ked = Departemen::create(['id_fakultas' => $f_kedokteran->id_fakultas, 'Nama_departemen' => 'Pendidikan Dokter']);

        // Catatan: Ini contoh representatif — Anda bisa minta saya tambahkan program studi spesifik dari Unand nanti
    }
}