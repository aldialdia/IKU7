<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = ['krs_id', 'komponen_id', 'nilai_angka'];

    /**
     * Relasi ke tabel KRS (Many-to-One).
     * Satu nilai milik satu KRS mahasiswa tertentu.
     */
    public function krs()
    {
        return $this->belongsTo(Krs::class, 'krs_id');
    }

    /**
     * Relasi ke tabel Komponen Penilaian (Many-to-One).
     * Satu nilai merujuk pada satu komponen (misal: "UTS" atau "Tugas").
     * * Method inilah yang dicari oleh 'with(nilai.komponen)'
     */
    public function komponen()
    {
        return $this->belongsTo(KomponenPenilaian::class, 'komponen_id');
    }
}