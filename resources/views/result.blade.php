@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <h1 class="text-center mb-4">Hasil Klasifikasi Penyakit</h1>
                <hr>
                <div>
                    @if ($m3['biasa'] > $m3['teta'])
                        <h3>Penyakit yang mungkin terjadi:</h3>
                        <ul>
                            <li>Penyakit 1</li>
                            <li>Penyakit 2</li>
                            <!-- Tambahkan daftar penyakit berdasarkan klasifikasi m3 -->
                        </ul>
                    @else
                        <p>Tidak ada hasil klasifikasi yang tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
