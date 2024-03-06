<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    protected $fillable = ['nama_penyakit', 'kode_penyakit']; // Atau gunakan $guarded jika lebih sesuai

    // Definisikan relasi dengan Gejala
    public function gejalas()
    {
        return $this->belongsToMany(Gejala::class);
    }
}
