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
        // Kita panggil seeder lain secara berurutan
        // Urutan ini penting karena ada relasi (Foreign Key)
        
        // 1. Seeder user (yang sudah kamu buat)
        $this->call(DummyUsersSeeder::class); 

        // 2. Seeder Fakultas & Departemen
        $this->call(FakultasDepartemenSeeder::class);

        // 3. Seeder Mata Kuliah & Komponen
        $this->call(MataKuliahSeeder::class);
    }
}