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
                <h5>Pilihan Dudi Siswa</h5>
                <button type="button ms-auto" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#tambahsiswa">Hitung Fuzzy</button>
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
                        Pilihan Dudi 1
                    </th>

                    <th>
                        Pilihan Dudi 2
                    </th>

                    <th>
                        Pilihan Dudi 3
                    </th>

                    <th>
                        Pilihan Dudi 4
                    </th>

                  

                </thead>

                <tr>
                    <td>
                        1
                    </td>
                    <td>
                        331545212
                    </td>
                    <td>
                        Joko
                    </td>
                    <td>
                        Dudi A
                    </td>
                    <td>
                        Dudi B
                    </td>
                    <td>
                        Dudi C
                    </td>
                    <td>
                        Dudi D
                    </td>
                </tr>

            </table>

        </div>


        <div>




        </div>

    </section>

@endsection

@section('script')


@endsection
