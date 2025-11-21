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
        Schema::table('krs', function (Blueprint $table) {
            $table->double('nilai_akhir')->nullable()->default(0)->after('kode_mk');
            $table->string('nilai_huruf', 2)->nullable()->after('nilai_akhir'); // A, A-, B+, dll
        });
    }

    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            $table->dropColumn(['nilai_akhir', 'nilai_huruf']);
        });
    }
};
