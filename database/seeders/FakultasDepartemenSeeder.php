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

        // 1. Buat Fakultas
        $fakultas1 = Fakultas::create(['Nama_fakultas' => 'Fakultas Ilmu Komputer']);
        $fakultas2 = Fakultas::create(['Nama_fakultas' => 'Fakultas Ekonomi dan Bisnis']);

        // 2. Buat Departemen untuk Fakultas 1
        Departemen::create([
            'id_fakultas' => $fakultas1->id_fakultas,
            'Nama_departemen' => 'Teknik Informatika'
        ]);
        Departemen::create([
            'id_fakultas' => $fakultas1->id_fakultas,
            'Nama_departemen' => 'Sistem Informasi'
        ]);

        // 3. Buat Departemen untuk Fakultas 2
        Departemen::create([
            'id_fakultas' => $fakultas2->id_fakultas,
            'Nama_departemen' => 'Manajemen'
        ]);
        Departemen::create([
            'id_fakultas' => $fakultas2->id_fakultas,
            'Nama_departemen' => 'Akuntansi'
        ]);
    }
}