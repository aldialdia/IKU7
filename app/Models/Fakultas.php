<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $table = 'fakultas'; // <-- TAMBAHKAN BARIS INI
    protected $primaryKey = 'id_fakultas';
    protected $fillable = ['Nama_fakultas'];

    // Relasi: Satu Fakultas punya banyak Departemen
    public function departemen()
    {
        return $this->hasMany(Departemen::class, 'id_fakultas', 'id_fakultas');
    }
}