jika kita memiliki gejala di awali di p1 kemudian dari input user tersebut memiliki adanya 2 atau 3 gejala p2
maka dari perhitungan gejala yang dari p1 ke p2 nya akan di simpan variabel
m3po1Baru = m3p01lama/1-conflict
m3p02Baru = m3p02lama/1-conflict
m3tetaBaru = m3tetalama/1-conflict

 kemudian dari variabel ini akan di hitung lagi mencari, konflikbaru2, m3p01baru2, m5p02kolom1baris1,m5p02kolom2baris1,m5p02kolom1baris2, m3tetabaru2,
konflikbaru2 == m3po1Baru*$m2['biasa'];
m3p01baru2 == m3po1Baru* $m2['teta'];
m5p02kolom1baris1 == m3p02Baru*$m2['biasa'];
m5p02kolom2baris1 == m3p02Baru*$m2['teta'];
m5p02kolom1baris2 == m3po1Baru*$m2['biasa'];
m3tetabaru2 == m3tetaBaru*$m2['teta'];

 setelah mendapat itu 
 kita update lagi nilai m3p01baru2, m5p02gabung, m3tetabaru2
 m3p01baru2 = m3p01baru2/(1-konflikbaru2)
 m5p02gabung = m5p02kolom1baris1+m5p02kolom2baris1+m5p02kolom1baris2/(1-konflikbaru2)
 m3tetabaru2 = m3tetabaru2/(1-konflikbaru2)
 ini yang akan di return