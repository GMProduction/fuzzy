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
                <h5>Data Nilai</h5>
                {{-- <button type="button ms-auto" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#tambahsiswa">Tambah Data</button> --}}
            </div>


            <table class="table table-striped table-bordered ">
                <thead>
                <tr>
                    <th>#</th>
                    <th>NIM</th>
                    <th>Nama Siswa</th>
                    <th>Nilai Rata2</th>
                    <th>Action</th>
                </tr>
                </thead>

                @forelse($data as $key => $d)
                    <tr>
                        <td>{{$data->firstItem() + $key}}</td>
                        <td>{{$d->siswa->nim}}</td>
                        <td>{{$d->siswa->nama}}</td>
                        <td>{{$d->avg ?? 0}}</td>
                        <td style="width: 150px">
                            <button type="button" class="btn btn-success btn-sm" data-id="{{$d->id}}" id="editData">Beri Nilai
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse

            </table>
            <div class="d-flex justify-content-end">
                {{$data->links()}}
            </div>
        </div>


        <div>


            <!-- Modal Tambah-->
            <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Penilaian</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form" onsubmit="return save()">
                                @csrf
                                <div id="formInput">
                                </div>
                                <div class="mb-4"></div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function () {

        });

        $(document).on('click', '#editData', function () {
            getPenilaian($(this).data('id'))
            $('#modal').modal('show')
        })

        function getPenilaian(id) {
            fetch('{{route('getAllMapel')}}')
            .then(response => response.json())
            .then(data => {
                var form = $('#formInput');
                form.empty();
                $.each(data, function (key, value) {
                    form.append(' <div id="mapel'+value['id']+'" class="mb-3">\n' +
                        '                                    <label for="mapel1" class="form-label" id="'+value['id']+'">'+value['nama']+' :</label>\n' +
                        '                                </div>')
                    fetch('/admin/nilai/by-siswa-mapel?user='+id+'&mapel='+value['id'])
                        .then(res => res.json())
                        .then(dat => {
                            var nilai = dat[0] ? dat[0]['nilai'] : '0';
                            $('#mapel'+value['id']).append('' +
                                '<input type="hidden" class="form-control" id="id" name="user['+key+']" value="'+id+'">\n' +
                                '<input type="hidden" class="form-control" id="id" name="mapel['+key+']" value="'+value['id']+'">\n' +
                                '               <input type="text" class="form-control" name="nilai['+key+']" value="'+nilai+'">');
                        })
                })


            })
        }

        function save() {
            saveData('Simpan data','form',null,after)
            return false

        }
        function after() {

        }

        function hapus(id, name) {
            swal({
                title: "Menghapus data?",
                text: "Apa kamu yakin, ingin menghapus data ?!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Berhasil Menghapus data!", {
                            icon: "success",
                        });
                    } else {
                        swal("Data belum terhapus");
                    }
                });
        }
    </script>

@endsection
