<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenPendukung extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'dokumen_pendukungs';

    // Tentukan kolom yang boleh diisi
    protected $fillable = ['kode_mk', 'nama_file', 'path_file'];

    /**
     * Relasi balik (Many-to-One):
     * Satu Dokumen Pendukung hanya dimiliki oleh satu MataKuliah.
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_mk', 'Kode_mk');
    }
}