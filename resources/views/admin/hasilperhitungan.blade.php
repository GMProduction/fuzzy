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
                <h5>Hasil Penempatan Dudi</h5>
                
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
                        Penempatan Dudi
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

                </tr>

            </table>

        </div>


        <div>




        </div>

    </section>

@endsection

@section('script')


@endsection
