@extends('layouts.app')

@section('content')
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
                                <th>No</th>
                                <th>Kode Gejala</th>
                                <th>Gejala</th>
                                <th>Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($gejala as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item['kode'] }}</td>
                                    <td>{{ $item['gejala'] }}</td>
                                    <td><input type="checkbox" name="gejala[]" value="{{ $item['kode'] }}"></td>
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
