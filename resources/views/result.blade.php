@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <h1 class="text-center mb-4">Hasil Klasifikasi Penyakit</h1>
                <hr>
                <div class="mb-4">
                    @if ($res['P01'] > $res['P02'])
                        <h4>Penyakit Ginjal Tidak Kronis:</h4>
                        <?php $persentaseBiasa = number_format($res['P01'] * 100, 2); ?>

                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentaseBiasa }}%;"
                                aria-valuenow="{{ $persentaseBiasa }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $persentaseBiasa }}%</div>
                        @else
                            <h4>Penyakit Ginjal Kronis:</h4>
                            <?php $persentaseBiasa = number_format($res['P02'] * 100, 2); ?>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $persentaseBiasa }}%;" aria-valuenow="{{ $persentaseBiasa }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ $persentaseBiasa }}%</div>
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
