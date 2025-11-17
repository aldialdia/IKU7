<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nama tabel jamak: dokumen_pendukungs
        Schema::create('dokumen_pendukungs', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->string('kode_mk'); // Foreign Key ke tabel mata_kuliah
            $table->string('nama_file'); // Nama file asli (cth: RPS_Alpro.pdf)
            $table->string('path_file'); // Path file yang disimpan (cth: public/dokumen_mk/xyz.pdf)
            $table->timestamps();

            // Relasi: Jika mata kuliah dihapus, dokumen terkait juga terhapus
            $table->foreign('kode_mk')->references('Kode_mk')->on('mata_kuliah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_pendukungs');
    }
};