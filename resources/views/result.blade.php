@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h1 class="text-center mb-4">Hasil Klasifikasi Penyakit</h1>
                <hr>
                <div class="mb-4">
                    @if ($res['P01'] > $res['P02'])
                        <h4>Penyakit Ginjal Kronis:</h4>
                        <?php $persentaseKronis = number_format($res['P01'] * 100, 2); ?>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentaseKronis }}%;"
                                aria-valuenow="{{ $persentaseKronis }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $persentaseKronis }}%</div>
                        </div>

                        <h4>Penyakit Ginjal Akut:</h4>
                        <?php $persentaseAkut = number_format($res['P02'] * 100, 2); ?>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $persentaseAkut }}%;"
                                aria-valuenow="{{ $persentaseAkut }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $persentaseAkut }}%</div>
                        </div>

                        <h4 class="text-center mt-4">Pengobatan Penyakit Ginjal Kronis:</h4>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Silahkan konsultasi ke dokter spesialis</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Minum antibiotik (jika ada infeksi)</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Minum obat herbal</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <h4>Penyakit Ginjal Akut:</h4>
                        <?php $persentaseAkut = number_format($res['P02'] * 100, 2); ?>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentaseAkut }}%;"
                                aria-valuenow="{{ $persentaseAkut }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $persentaseAkut }}%</div>
                        </div>

                        <h4>Penyakit Ginjal Kronis:</h4>
                        <?php $persentaseKronis = number_format($res['P01'] * 100, 2); ?>
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $persentaseKronis }}%;"
                                aria-valuenow="{{ $persentaseKronis }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $persentaseKronis }}%</div>
                        </div>

                        <h4 class="text-center mt-4">Pengobatan Penyakit Ginjal Akut:</h4>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Obat-obatan untuk mengontrol tekanan darah dan kadar gula darah</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Diet khusus</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Dialisis</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Transplasi ginjal</td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                    <a class="btn btn-primary" href="{{ route('home') }}" role="button">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
