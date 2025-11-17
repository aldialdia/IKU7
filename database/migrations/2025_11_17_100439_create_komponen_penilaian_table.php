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
        Schema::create('komponen_penilaian', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk');
            $table->string('nama_komponen'); // Cth: 'Proyek', 'UTS'
            $table->integer('persentase'); // Cth: 50, 20
            $table->timestamps();

            $table->foreign('kode_mk')->references('Kode_mk')->on('mata_kuliah')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_penilaian');
    }
};
