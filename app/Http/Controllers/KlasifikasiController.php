<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KlasifikasiController extends Controller
{
    public function home()
    {
        $gejala = [
            ['kode' => 'G1', 'gejala' => 'Adanya darah dalam urine'],
            ['kode' => 'G2', 'gejala' => 'Sesak Nafas'],
            ['kode' => 'G3', 'gejala' => 'Lemas'],
            ['kode' => 'G4', 'gejala' => 'Nyeri Pada Ulu Hati'],
            ['kode' => 'G5', 'gejala' => 'Merasa Lapar dan Kelelahan'],
            ['kode' => 'G6', 'gejala' => 'Merasa Haus berlebihan'],
            ['kode' => 'G7', 'gejala' => 'Penglihatan Kabur'],
            ['kode' => 'G8', 'gejala' => 'Demam'],
            ['kode' => 'G9', 'gejala' => 'Tekanan Darah Rendah'],
            ['kode' => 'G10', 'gejala' => 'Sakit Kepala'],
            ['kode' => 'G11', 'gejala' => 'Denyut Jantung Cepat'],
            ['kode' => 'G12', 'gejala' => 'Nyeri Punggung'],
        ];

        return view('home', compact('gejala'));
    }
    public function classify(Request $request)
    {
        $request->validate([
            'gejala' => 'required|array',
            'gejala.*' => 'required|string|in:G1,G2,G3,G4,G5,G6,G7,G8,G9,G10,G11,G12', // Pastikan setidaknya satu gejala dari G1 hingga G6 dipilih
        ]);
        // Gejala dan bobotnya
        $gejala = [
            'G1' => 0.95,
            'G2' => 0.80,
            'G3' => 0.85,
            'G4' => 0.85,
            'G5' => 0.5,
            'G6' => 0.5,
            'G7' => 0.35,
            'G8' => 0.45,
            'G9' => 0.5,
            'G10' => 0.55,
            'G11' => 0.45,
            'G12' => 0.4
        ];

        // Pembagian gejala
        $p1 = ['G1', 'G2', 'G3', 'G4'];
        $p2 = ['G5', 'G6', 'G7', 'G8', 'G9', 'G10', 'G11', 'G12'];

        $input_user = $request->gejala;
        $result = $this->calculate($gejala, $p1, $p2, $input_user);
        $m3 = $result['m3'];
        $isp2 = $result['isp2'];
        return view('result', compact('m3', 'isp2'));
    }

    public function check_p2_only($input_user, $p1)
    {
        foreach ($input_user as $input) {
            if (in_array($input, $p1)) {
                return false;
            }
        }
        return true;
    }

    public function calculate_m3($m3, $gejala, $input, $p1, $p2)
    {
        $m2 = ['biasa' => $gejala[$input], 'teta' => 1 - $gejala[$input]];

        $conflict = $m3['biasa'] * $m2['biasa'];
        $m3_p01 = $m3['biasa'] * $m2['teta'];
        $m3_p02 = $m3['teta'] * $m2['biasa'];
        $m3_theta = $m3['teta'] * $m2['teta'];
        if (in_array($input, $p1)) {
            return ['biasa' => $conflict + $m3_p01 + $m3_p02, 'teta' => $m3_theta];
        } elseif (in_array($input, $p2)) {
            return ['biasa' => $m3_p01 / (1 - $conflict), 'teta' => $m3_theta];
        }
    }

    public function calcultate_m3_p2($m3, $gejala, $input, $p2, $p1)
    {
        $m2 = ['biasa' => $gejala[$input], 'teta' => 1 - $gejala[$input]];

        $conflict = $m3['biasa'] * $m2['biasa'];
        $m3_p01 = $m3['biasa'] * $m2['teta'];
        $m3_p02 = $m3['teta'] * $m2['biasa'];
        $m3_theta = $m3['teta'] * $m2['teta'];
        return ['biasa' => $conflict + $m3_p01 + $m3_p02, 'teta' => $m3_theta];
    }

    public function calculate($gejala, $p1, $p2, $input_user)
    {
        $m3 = ['biasa' => $gejala[$input_user[0]], 'teta' => 1 - $gejala[$input_user[0]]];
        $isp2 = false;

        if ($this->check_p2_only($input_user, $p1)) {
            for ($i = 1; $i < count($input_user); $i++) {
                $m3 = $this->calcultate_m3_p2($m3, $gejala, $input_user[$i], $p2, $p1);
            }
            $isp2 = true;
        } else {
            for ($i = 1; $i < count($input_user); $i++) {
                $m3 = $this->calculate_m3($m3, $gejala, $input_user[$i], $p1, $p2);
            }
        }

        return [
            'm3' => $m3,
            'isp2' => $isp2
        ];
    }
}
