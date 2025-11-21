<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    protected $table = 'krs';
    protected $fillable = ['mahasiswa_id', 'kode_mk'];

    public function mahasiswa() {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah() {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'Kode_mk');
    }

    // Ambil semua nilai milik KRS ini
    public function nilai() {
        return $this->hasMany(Nilai::class, 'krs_id');
    }
}
