<?php

namespace App;

use Illuminate\Support\Facades\Log;

class DempsterShaffer
{
    private $gejala = [
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

    private $aturan = [
        'P1' => ['G1', 'G2', 'G3', 'G4'],
        'P2' => ['G5', 'G6', 'G7', 'G8', 'G9', 'G10', 'G11', 'G12']
    ];

    public $gejalas = [];
    public $penyakits = [];

    public function __construct()
    {
        foreach ($this->gejala as $kode => $bobot) {
            $this->gejalas[$kode] = new Gejala($kode, $bobot);
        }
    }


    public function getGejalas()
    {
        return $this->gejalas;
    }

    public function getPenyakits()
    {
        return $this->penyakits;
    }

    public function cekPenyakits($gejala, $gejala2)
    {
        $penyakitBobot = $gejala->getPenyakitBobot();
        $penyakitBobot2 = $gejala2->getPenyakitBobot();
        if ($gejala->kode === "null" && $gejala2->kode === "null") {
            return true;
        }
        if ($gejala->kode === "0" && $gejala2->kode === "0") {
            return true;
        }
        if ($penyakitBobot === null || $penyakitBobot2 === null || count($penyakitBobot) !== count($penyakitBobot2) || $penyakitBobot === null || $penyakitBobot2 === null) {
            return false;
        }
        for ($i = 0; $i < count($penyakitBobot); $i++) {
            $penyakit = $penyakitBobot[$i]->first;
            $penyakit2 = $penyakitBobot2[$i]->first;
            if ($penyakit !== null && $penyakit->kode !== $penyakit2->kode) {
                return false;
            }
        }
        return true;
    }

    public function cross($gejala, $gejala2)
    {
        $gejala3 = new Gejala("123");
        if ($gejala->kode === "0" && $gejala2->kode === "0") {
            $gejala3->kode = "0";
        } elseif ($gejala->kode === "0") {
            $gejala3->penyakitBobots = $gejala2->penyakitBobots;
        } elseif ($gejala2->kode === "0") {
            $gejala3->penyakitBobots = $gejala->penyakitBobots;
        } else {
            $gejala3->penyakitBobots = [];
            $it = $gejala->getPenyakitBobot()->getIterator();
            while ($it->valid()) {
                $next = $it->current();
                $it2 = $gejala2->getPenyakitBobot()->getIterator();
                while ($it2->valid()) {
                    $penyakit = $next->first;
                    $penyakit2 = $it2->current()->first;
                    if ($penyakit !== null) {
                        if ($penyakit->kode === $penyakit2->kode) {
                            $gejala3->addPenyakitBobot($penyakit, 0.0);
                        }
                    }
                    $it2->next();
                }
                $it->next();
            }
            if (count($gejala3->penyakitBobots) === 0) {
                $gejala3->kode = "null";
            }
        }
        $gejala3->bobotAvg = $gejala->bobotAvg * $gejala2->bobotAvg;
        return $gejala3;
    }

    public function perkalianTabel($arrayList, $arrayList2)
    {
        $arrayList3 = [];
        $it = $arrayList->getIterator();
        while ($it->valid()) {
            $next = $it->current();
            $it2 = $arrayList2->getIterator();
            while ($it2->valid()) {
                $cross = $this->cross($next, $it2->current());
                $z = false;
                $it3 = new \ArrayIterator($arrayList3);
                while ($it3->valid()) {
                    $next2 = $it3->current();
                    if ($this->cekPenyakits($next2, $cross)) {
                        $next2->bobotAvg += $cross->bobotAvg;
                        $z = true;
                    }
                    $it3->next();
                }
                if (!$z) {
                    $arrayList3[] = $cross;
                }
                $it2->next();
            }
            $it->next();
        }
        $d = 0.0;
        $gejala = null;
        $it4 = new \ArrayIterator($arrayList3);
        while ($it4->valid()) {
            $next3 = $it4->current();
            if ($next3->kode === "null") {
                $d = $next3->bobotAvg;
                $gejala = $next3;
            }
            $it4->next();
        }
        unset($arrayList3[array_search($gejala, $arrayList3)]);
        $it5 = new \ArrayIterator($arrayList3);
        while ($it5->valid()) {
            $it5->current()->bobotAvg /= 1.0 - $d;
            $it5->next();
        }
        return $arrayList3;
    }

    public function findGejala($str)
    {
        return $this->gejalas[$str] ?? null;
    }

    public function predict($strArr)
    {
        $linkedList = new \SplQueue();
        foreach ($strArr as $str) {
            $findGejala = $this->findGejala($str);
            if ($findGejala !== null) {
                $arrayList = new \SplQueue();
                $arrayList->enqueue($findGejala);
                $arrayList->enqueue(new Gejala("0", 1.0 - $findGejala->bobotAvg));
                $linkedList->enqueue($arrayList);
            }
        }
        $arrayList2 = null;
        while ($linkedList->count() > 0) {
            if ($arrayList2 === null) {
                $arrayList2 = $this->perkalianTabel($linkedList->dequeue(), $linkedList->dequeue());
            } else {
                $arrayList2 = $this->perkalianTabel($arrayList2, $linkedList->dequeue());
            }
        }
        if ($arrayList2 === null) {
            $arrayList2 = []; // Inisialisasi dengan array kosong jika null
        }
        return $arrayList2;
    }

    public function a($i, $str, $str2, array $iArr, array $dArr)
    {
        foreach ($iArr as $index => $value) {
            Log::info("panjang " . strval($this->gejala[$value])); // Menggunakan Log::info()
            $this->penyakits[$value] = new Penyakit($this->gejala[$value], 'P' . ($value + 1));
        }
        $this->gejalas[$i] = new Gejala($str, $str2);
        foreach ($iArr as $index => $value) {
            $this->gejalas[$i]->addPenyakitBobot($this->penyakits[$value], $dArr[0]);
            Log::info("test " . strval($value)); // Menggunakan Log::info()
        }
    }
}
