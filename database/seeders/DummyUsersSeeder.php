<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Fakultas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $fakultas1 = Fakultas::where('Nama_fakultas', 'Fakultas Teknologi Informasi')->first();

        $userData = [
            [
                'name' => 'Admin Rektorat',
                'email' => 'Rektorat@gmail.com',
                'password' => bcrypt('adm123'),
                'role' => 'rektorat',
                'id_fakultas' => null,
            ],
            [
                'name' => 'Admin Fakultas Teknologi Informasi',
                'email' => 'Fakultas1@gmail.com',
                'password' => bcrypt('adm123'),
                'role' => 'fakultas',
                'id_fakultas' => $fakultas1->id_fakultas,
            ],
            [
                'name' => 'Pak Dosen1',
                'email' => 'Dosen1@gmail.com',
                'password' => bcrypt('adm123'),
                'role' => 'dosen',
                'id_fakultas' => $fakultas1->id_fakultas,
            ],
            [
                'name' => 'Pak Dosen2',
                'email' => 'Dosen2@gmail.com',
                'password' => bcrypt('adm123'),
                'role' => 'dosen',
                'id_fakultas' => $fakultas1->id_fakultas,
            ]
        ];

        foreach ($userData as $key => $val) {
            User::create($val);
        }
        
    }
}
