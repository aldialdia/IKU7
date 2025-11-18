<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom id_fakultas setelah kolom 'Role'
            $table->foreignId('id_fakultas')
                  ->nullable() // Boleh NULL (untuk Rektorat & Dosen)
                  ->after('Role')
                  ->constrained('fakultas', 'id_fakultas') // Merujuk ke tabel 'fakultas'
                  ->onDelete('set null'); // Jika fakultas dihapus, user-nya jadi NULL
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Logika untuk rollback (drop foreign key & kolom)
            $table->dropForeign(['id_fakultas']);
            $table->dropColumn('id_fakultas');
        });
    }
};