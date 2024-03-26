<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KlasifikasiController extends Controller
{

    private $penyakit = [
        'G1'    => 1,
        'G2'    => 1,
        'G3'    => 1,
        'G4'    => 1,
        'G5'    => 2,
        'G6'    => 2,
        'G7'    => 2,
        'G8'    => 2,
        'G9'    => 2,
        'G10'    => 2,
        'G11'    => 2,
        'G12'    => 2,
    ];

    private $penyakit_bobot = [
        'G1'    => 0.95,
        'G2'    => 0.8,
        'G3'    => 0.85,
        'G4'    => 0.85,
        'G5'    => 0.75,
        'G6'    => 0.75,
        'G7'    => 0.8,
        'G8'    => 0.75,
        'G9'    => 0.85,
        'G10'    => 0.7,
        'G11'    => 0.75,
        'G12'    => 0.8,
    ];

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
            'G5' => 0.75,
            'G6' => 0.75,
            'G7' => 0.80,
            'G8' => 0.75,
            'G9' => 0.85,
            'G10' => 0.70,
            'G11' => 0.75,
            'G12' => 0.80
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


    public function classifyOke(Request $request)
    {
        $input = $request->gejala;

        $curr_m3_p01        = 0;
        $curr_m3_p02        = 0;
        $curr_m3_teta       = 0;
        $is_udah_konflik    = 0;

        for ($i = 1; $i < count($input); $i++) {

            if ($this->penyakit[$input[$i - 1]] != $this->penyakit[$input[$i]] || $is_udah_konflik >= 1) {
                $is_udah_konflik++;
            }

            //Dengan yang sebelumnya
            if ($i == 1) {
                //Kalau Masih diawal
                $res = $this->hitung_m($this->penyakit_bobot[$input[$i - 1]], 1 - $this->penyakit_bobot[$input[$i - 1]], $curr_m3_p02, $this->penyakit_bobot[$input[$i]], 1 - $this->penyakit_bobot[$input[$i]], $is_udah_konflik, min($this->penyakit[$input[$i - 1]], $this->penyakit[$input[$i]]));

                $curr_m3_p01 = $res["P01"];
                $curr_m3_p02 = $res['P02'];
                $curr_m3_teta = $res['Teta'];
            } else {
                //Kalau Udah kedua
                $res = $this->hitung_m($curr_m3_p01, $curr_m3_teta, $curr_m3_p02, $this->penyakit_bobot[$input[$i]], 1 - $this->penyakit_bobot[$input[$i]], $is_udah_konflik, min($this->penyakit[$input[$i - 1]], $this->penyakit[$input[$i]]));

                $curr_m3_p01 = $res["P01"];
                $curr_m3_p02 = $res['P02'];
                $curr_m3_teta = $res['Teta'];
            }
            // return $res;
        }
        // dd($res);
        return view('result', compact('res'));
    }
    public function hitung_m($gejala_1, $gejala_1_theta, $gejala_1_2, $gejala_2, $gejala_2_theta, $is_konflik = 0, $p_duluan)
    {

        if (!$is_konflik) {
            //Kalau Belum konflik
            $m1_m2 = $gejala_1 * $gejala_2;
            $m1_m2teta = $gejala_1 * $gejala_2_theta;
            $m1teta_m2 = $gejala_1_theta * $gejala_2;
            $m1teta_m2teta = $gejala_1_theta * $gejala_2_theta;

            //Karena dia gak konflik
            if ($p_duluan == 1) {
                $ans = array(
                    'P01'   => $m1_m2 + $m1_m2teta + $m1teta_m2,
                    'P02'   => 0,
                    'Teta'  => $m1teta_m2teta,
                );
            } else {
                $ans = array(
                    'P01'   => 0,
                    'P02'   => $m1_m2 + $m1_m2teta + $m1teta_m2,
                    'Teta'  => $m1teta_m2teta,
                );
            }

            return $ans;
        } else if ($is_konflik == 1) {
            $konflik = $gejala_1 * $gejala_2;
            $m1_m2teta = $gejala_1 * $gejala_2_theta;
            $m1teta_m2 = $gejala_1_theta * $gejala_2;
            $m1teta_m2teta = $gejala_1_theta * $gejala_2_theta;

            $ans = array(
                'P01'   => $m1_m2teta / (1 - $konflik),
                'P02'   => $m1teta_m2 / (1 - $konflik),
                'Teta'  => $m1teta_m2teta / (1 - $konflik)
            );

            return $ans;
        } else if ($is_konflik > 1) {
            //Kalau Konflik
            $konflik    = $gejala_1 * $gejala_2;
            $m1_m2teta  = $gejala_1 * $gejala_2_theta;
            $m12_m2     = $gejala_1_2 * $gejala_2;
            $m12_m2teta = $gejala_1_2 * $gejala_2_theta;
            $m1teta_m2  = $gejala_1_theta * $gejala_2;
            $m1teta_m2teta = $gejala_1_theta * $gejala_2_theta;

            $ans = array(
                'P01'   => $m1_m2teta / (1 - $konflik),
                'P02'   => ($m12_m2 + $m12_m2teta + $m1teta_m2) / (1 - $konflik),
                'Teta'  => $m1teta_m2teta / (1 - $konflik)
            );

            return $ans;
        }
    }
}
