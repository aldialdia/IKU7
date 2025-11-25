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
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->string('Kode_mk')->primary();
            // Ini merujuk ke tabel 'users' kolom 'id' (standar)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('id_departemen')->constrained('departemen', 'id_departemen')->onDelete('cascade');
            $table->string('Nama_mk');
            $table->string('Semester_mk');
            $table->integer('SKS');
            $table->string('Metode')->nullable(); // PjBL, CBM, atau Biasa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
