<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Fakultas & Departemen DULU
        $this->call(FakultasDepartemenSeeder::class);
        
        // 2. BARU Buat User (yang membutuhkan ID Fakultas)
        $this->call(DummyUsersSeeder::class); 

        // 3. Terakhir, Buat Mata Kuliah (yang membutuhkan ID Dosen & Departemen)
        $this->call(MataKuliahSeeder::class);
    }
}