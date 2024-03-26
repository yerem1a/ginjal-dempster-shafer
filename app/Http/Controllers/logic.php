<?php
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

function check_p2_only($input_user, $p1)
{
    foreach ($input_user as $input) {
        if (in_array($input, $p1)) {
            return false;
        }
    }
    return true;
}

function calculate_m3($m3, $gejala, $input, $p1, $p2)
{
    $m2 = ['biasa' => $gejala[$input], 'teta' => 1 - $gejala[$input]];

    $conflict = $m3['biasa'] * $m2['biasa'];
    $m3_p01 = $m3['biasa'] * $m2['teta'];
    $m3_p02 = $m3['teta'] * $m2['biasa'];
    $m3_theta = $m3['teta'] * $m2['teta'];
    if (in_array($input, $p1)) {
        return ['biasa' => $conflict + $m3_p01 + $m3_p02, 'teta' => $m3_theta / (1 - $conflict)];
    } elseif (in_array($input, $p2)) {
        return ['biasa' => ($m3_p01 + $m3_p02) / (1 - $conflict), 'teta' => $m3_theta];
    }
}

//jika kita memiliki gejala di awali di p1 kemudian 2 atau 3 gejala p2
//maka dari perhitungan gejala yang dari p1 ke p2 nya akan di simpan variabel
//m3po1Baru = m3p01lama/1-conflict
//m3p02Baru = m3p02lama/1-conflict
//m3tetaBaru = m3tetalama/1-conflict

// kemudian dari variabel ini akan di hitung lagi mencari, konflikbaru2, m3p01baru2, m5p02kolom1baris1,m5p02kolom2baris1,m5p02kolom1baris2, m3tetabaru2,
//konflikbaru2 == m3po1Baru*$m2['biasa'];
//m3p01baru2 == m3po1Baru* $m2['teta'];
//m5p02kolom1baris1 == m3p02Baru*$m2['biasa'];
//m5p02kolom2baris1 == m3p02Baru*$m2['teta'];
//m5p02kolom1baris2 == m3po1Baru*$m2['biasa'];
//m3tetabaru2 == m3tetaBaru*$m2['teta'];

// setelah mendapat itu 
// kita update lagi nilai m3p01baru2, m5p02gabung, m3tetabaru2
// m3p01baru2 = m3p01baru2/(1-konflikbaru2)
// m5p02gabung = m5p02kolom1baris1+m5p02kolom2baris1+m5p02kolom1baris2/(1-konflikbaru2)
// m3tetabaru2 = m3tetabaru2/(1-konflikbaru2)
// ini yang akan di return


function calcultate_m3_p2($m3, $gejala, $input, $p2, $p1)
{
    $m2 = ['biasa' => $gejala[$input], 'teta' => 1 - $gejala[$input]];

    $conflict = $m3['biasa'] * $m2['biasa'];
    $m3_p01 = $m3['biasa'] * $m2['teta'];
    $m3_p02 = $m3['teta'] * $m2['biasa'];
    $m3_theta = $m3['teta'] * $m2['teta'];
    return ['biasa' => $conflict + $m3_p01 + $m3_p02, 'teta' => $m3_theta];
}

function calculate($gejala, $p1, $p2, $input_user)
{
    $m3 = ['biasa' => $gejala[$input_user[0]], 'teta' => 1 - $gejala[$input_user[0]]];
    $isp2 = false;

    if (check_p2_only($input_user, $p1)) {
        for ($i = 1; $i < count($input_user); $i++) {
            $m3 = calcultate_m3_p2($m3, $gejala, $input_user[$i], $p2, $p1);
        }
        $isp2 = true;
    } else {
        for ($i = 1; $i < count($input_user); $i++) {
            $m3 = calculate_m3($m3, $gejala, $input_user[$i], $p1, $p2);
        }
    }

    return [
        'm3' => $m3,
        'isp2' => $isp2
    ];
}

// Contoh penggunaan
$input_user = ['G2', 'G5', 'G8'];
$result = calculate($gejala, $p1, $p2, $input_user);
$m3 = $result['m3'];
$isp2 = $result['isp2'];
print_r($m3);
echo "<br>";
echo "ISP2: " . ($isp2 ? 'True' : 'False');
