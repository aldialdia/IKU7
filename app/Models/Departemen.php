<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;
    
    protected $table = 'departemen'; // <-- TAMBAHKAN BARIS INI
    protected $primaryKey = 'id_departemen';
    protected $fillable = ['Nama_departemen', 'id_fakultas'];

    // Relasi: Satu Departemen milik satu Fakultas
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_fakultas', 'id_fakultas');
    }

    // Relasi: Satu Departemen punya banyak MataKuliah
    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class, 'id_departemen', 'id_departemen');
    }
}