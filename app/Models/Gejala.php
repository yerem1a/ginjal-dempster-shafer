<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use App\Models\Penyakit;
use App\Models\Log;

class Gejala extends Model
{
    protected $fillable = ['kode', 'nama_gejala']; // Atau gunakan $guarded jika lebih sesuai

    // Definisikan relasi dengan Penyakit
    public function penyakit()
    {
        return $this->belongsToMany(Penyakit::class);
    }

    // Definisikan relasi dengan Log (jika diperlukan)
    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
