<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['message', 'level', 'context']; // Atau gunakan $guarded jika lebih sesuai

    // Definisikan relasi dengan Gejala
    public function gejala()
    {
        return $this->belongsTo(Gejala::class);
    }
}
