<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;
    
    // Memberi tahu Laravel nama tabel kita
    protected $table = 'mata_kuliah'; 
    
    // Memberi tahu Laravel bahwa Primary Key kita adalah 'Kode_mk'
    protected $primaryKey = 'Kode_mk'; 
    
    // Memberi tahu Laravel bahwa PK kita BUKAN auto-increment
    public $incrementing = false; 
    
    // Memberi tahu Laravel bahwa tipe data PK kita adalah string
    protected $keyType = 'string'; 

    protected $fillable = [
        'Kode_mk',
        'user_id', // Ini adalah foreign key ke tabel users
        'id_departemen',
        'Nama_mk',
        'Semester_mk',
        'SKS',
        'Metode',
        'verified',
    ];

    /**
     * Relasi: Satu MataKuliah milik satu Dosen (User).
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi: Satu MataKuliah milik satu Departemen.
     */
    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'id_departemen', 'id_departemen');
    }

    /**
     * Relasi: Satu MataKuliah punya banyak KomponenPenilaian.
     */
    public function komponenPenilaian()
    {
        return $this->hasMany(KomponenPenilaian::class, 'kode_mk', 'Kode_mk');
    }

    /**
     * === TAMBAHKAN RELASI BARU INI ===
     * Relasi (One-to-Many):
     * Satu MataKuliah bisa memiliki banyak DokumenPendukung.
     */
    public function dokumenPendukung()
    {
        return $this->hasMany(DokumenPendukung::class, 'kode_mk', 'Kode_mk');
    }
}