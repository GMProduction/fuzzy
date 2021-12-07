@extends('admin.base')

@section('title')
    Data Siswa
@endsection

@section('content')

    @if (\Illuminate\Support\Facades\Session::has('success'))
        <script>
            swal("Berhasil!", "Berhasil Menambah data!", "success");
        </script>
    @endif

    <section class="m-2">


        <div class="table-container">


            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5>Pilihan Tempat Magang Siswa</h5>
                {{--                <button type="button ms-auto" class="btn btn-primary btn-sm" data-bs-toggle="modal"--}}
                {{--                        data-bs-target="#tambahsiswa">Hitung Fuzzy--}}
                {{--                </button>--}}
            </div>


            <table class="table table-striped table-bordered ">
                <thead>
                <th>
                    #
                </th>
                <th>
                    NIM
                </th>
                <th>
                    Nama Siswa
                </th>
                <th>
                    Pilihan 1
                </th>

                <th>
                    Pilihan 2
                </th>

                <th>
                    Pilihan 3
                </th>
                <th>

                </th>
                </thead>

                <tbody>
                @foreach($siswa as $v)
                    <tr>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            {{ $v->siswa->nim }}
                        </td>
                        <td>
                            {{ $v->siswa->nama }}
                        </td>
                        <td>
                            {{ $v->pilihanmagang[0]->dudi->dudi->nama }}
                        </td>
                        <td>
                            {{ $v->pilihanmagang[1]->dudi->dudi->nama }}
                        </td>
                        <td>
                            {{ $v->pilihanmagang[2]->dudi->dudi->nama }}
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm btn-hasil" data-id="{{ $v->id }}"
                                    data-bs-toggle="modal" data-bs-target="#exampleModal">Hasil
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div>
        </div>

    </section>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Penilaian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Pilihan Magang</p>
                    <div id="score-result">
                        <div>
                            <p class="mb-0">Telkom Klaten</p>
                            <table class="table table-striped table-bordered ">
                                <thead>
                                <th>#</th>
                                <th>Mata Pelajaran</th>
                                <th>Nilai</th>
                                <th>Indicator</th>
                                </thead>
                                <tbody>
                                <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>


        function createTable(data) {
            let subjects = '';
            $.each(data['subjects'], function (k, v) {
                subjects += '<tr>' +
                    '<td>' + (k + 1) + '</td>' +
                    '<td>' + v['name'] + '</td>' +
                    '<td>' + v['score'] + '</td>' +
                    '<td>' + v['score_indicator'] + '</td>' +
                    '</tr>';
            });

            return '<table class="table table-striped table-bordered ">' +
                '<thead>' +
                '   <th>#</th>' +
                '   <th>Mata Pelajaran</th>' +
                '   <th>Nilai</th>' +
                '   <th>Indikator</th>' +
                '</thead>' +
                '<tbody>' + subjects +
                '</tbody>' +
                '</table>' +
                '<div class="d-flex">' +
                '<p class="mb-0 flex-grow-1" style="font-weight: bold">Presentase Rule Instansi :</p>' +
                '<p class="mb-0" style="font-weight: bold">' + data['rule']['percentage'] + '</p>' +
                '</div>' +
                '<div class="d-flex">' +
                '<p class="mb-0 flex-grow-1" style="font-weight: bold">Total Defuzzifikazi :</p>' +
                '<p class="mb-0" style="font-weight: bold">' + data['defuzzifikasi']['total'] + '</p>' +
                '</div>';
        }

        function createElement(data) {


            let main = '';
            $.each(data, function (k, v) {
                main += '<div class="mb-4">' +
                    '<p class="mb-1" style="font-weight: bold">' + v['nama'] + '</p>' + createTable(v) +
                    '</div>';
            });
            return main;
        }

        async function nilai(id) {
            let el = $('#score-result');
            try {
                let response = await $.get('/cek-nilai-siswa?id=' + id);
                el.empty();
                if (response['status'] === 200) {
                    el.append(createElement(response['payload']['sorted']));
                }
                console.log(response);
            } catch (e) {
                console.log(e)
            }
        }

        $(document).ready(function () {
            $('.btn-hasil').on('click', function () {
                let id = this.dataset.id;
                nilai(id)
            })
        })
    </script>

@endsection
