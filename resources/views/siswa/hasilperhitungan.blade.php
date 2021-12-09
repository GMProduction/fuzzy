@extends('siswa.base')

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
                <h5>Hasil Penempatan Dudi</h5>
{{--                <button type="button" class="btn btn-primary btn-sm btn-hasil" data-id="{{ auth()->id() }}">Lihat Hasil--}}
{{--                    Saya--}}
{{--                </button>--}}

            </div>


            <table class="table table-striped table-bordered " id="myTable">
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
                    Penempatan Dudi
                </th>
                </thead>
                <tbody>
                @foreach($result as $r)
                    <tr>
                        <td>
                            {{ $loop->index + 1 }}
                        </td>
                        <td>
                            {{ $r['nim'] }}
                        </td>
                        <td>
                            {{ $r['nama'] }}
                        </td>
                        <td>
                            {{ count($r['pilihan']) > 0 ? $r['pilihan'][0]['nama'] : 'Belum Ada Penilaian' }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>

@endsection
