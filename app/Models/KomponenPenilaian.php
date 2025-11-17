<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenPenilaian extends Model
{
    use HasFactory;
    
    // Nama tabelnya kita set eksplisit
    protected $table = 'komponen_penilaian'; 

    protected $fillable = [
        'kode_mk',
        'nama_komponen',
        'persentase',
    ];

    // Relasi: Satu Komponen milik satu MataKuliah
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'Kode_mk');
    }
}