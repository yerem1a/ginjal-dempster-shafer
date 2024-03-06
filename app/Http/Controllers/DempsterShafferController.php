<?php

namespace App\Http\Controllers;

use App\DempsterShaffer;
use Illuminate\Http\Request;

class DempsterShafferController extends Controller
{
    public function predict(Request $request)
    {
        // Inisialisasi DempsterShaffer
        $dempsterShaffer = new DempsterShaffer();

        // Ambil input dari pengguna
        $inputGejala = $request->input('gejala');

        // Melakukan prediksi berdasarkan input pengguna
        $hasilPrediksi = $dempsterShaffer->predict($inputGejala);

        // Tampilkan hasil prediksi
        foreach ($hasilPrediksi as $gejala) {
            echo "Gejala: " . $gejala->kode . "\n";
            // Tampilkan informasi tambahan atau lakukan sesuai kebutuhan
        }
    }
}
