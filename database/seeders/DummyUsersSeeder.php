<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Admin Rektorat',
                'email' => 'Rektorat@gmail.com',
                'password' => bcrypt('adm123'),
                'role' => 'rektorat',
            ],
            [
                'name' => 'Admin Fakultas1',
                'email' => 'Fakultas@gmail.com',
                'password' => bcrypt('adm123'),
                'role' => 'fakultas',
            ],
            [
                'name' => 'Pak Dosen1',
                'email' => 'Dosen1@gmail.com',
                'password' => bcrypt('adm123'),
                'role' => 'dosen',
            ],
            [
                'name' => 'Pak Dosen2',
                'email' => 'Dosen2@gmail.com',
                'password' => bcrypt('adm123'),
                'role' => 'dosen',
            ]
        ];

        foreach ($userData as $key => $val) {
            User::create($val);
        }
        
    }
}
