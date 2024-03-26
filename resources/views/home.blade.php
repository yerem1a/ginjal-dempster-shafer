@extends('layouts.app')

@section('content')
    <style>
        .center-text {
            text-align: center;
        }

        .center-checkbox {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }
    </style>
    <div class="container">
        <div class="card mt-5">
            <div class="card-body">
                <h1 class="text-center mb-4">Klasifikasi Penyakit Ginjal</h1>
                <hr>
                <form id="classificationForm" action="{{ route('classify') }}" method="post">
                    @csrf
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="center-text">No</th>
                                <th class="center-text">Kode Gejala</th>
                                <th class="center-text">Gejala</th>
                                <th class="center-text">Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gejala as $key => $item)
                                <tr>
                                    <td class="center-text">{{ $key + 1 }}</td>
                                    <td class="center-text">{{ $item['kode'] }}</td>
                                    <td class="center-text">{{ $item['gejala'] }}</td>
                                    <td class="center-checkbox"><input type="checkbox" name="gejala[]"
                                            value="{{ $item['kode'] }}" style="transform: scale(1.5);"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary mt-3" onclick="submitForm()">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function submitForm() {
            var checkboxes = document.querySelectorAll('input[name="gejala[]"]:checked');
            if (checkboxes.length < 2) {
                alert("Pilih minimal dua gejala.");
            } else {
                document.getElementById('classificationForm').submit();
            }
        }
    </script>
@endsection
