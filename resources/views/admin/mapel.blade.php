@extends('admin.base')

@section('title')
    Data Mapel
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
                <h5>Data Mapel</h5>
                <button type="button" class="btn btn-primary btn-sm" id="addData">Tambah Data</button>
            </div>


            <table class="table table-striped table-bordered ">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Mapel</th>
                    <th>Alias</th>
                    <th>Action</th>
                </tr>
                </thead>
                @forelse($data as $key => $d)
                    <tr>
                        <td>{{$data->firstItem() + $key}}</td>
                        <td>{{$d->nama}}</td>
                        <td>{{$d->alias}}</td>
                        <td style="width: 150px">
                            <a type="button" class="btn btn-success btn-sm" id="editData" data-id="{{$d->id}}" data-nama="{{$d->nama}}" data-alias="{{$d->alias}}">Ubah</a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapus('id', 'nama') ">hapus</button>
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
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Mapel</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form" onsubmit="return save()">
                                @csrf
                                <input type="hidden" id="id" name="id">
                                <div class="mb-3">
                                    <label for="namamapel" class="form-label">Nama Mapel</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>

                                <div class="mb-3">
                                    <label for="singkatan" class="form-label">Alias</label>
                                    <input type="text" class="form-control" id="alias" name="alias" required>
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

        })

        $(document).on('click', '#addData, #editData', function () {
            $('#modal #id').val($(this).data('id'))
            $('#modal #nama').val($(this).data('nama'))
            $('#modal #alias').val($(this).data('alias'))
            $('#modal').modal('show')
        })

        function save() {
            saveData('Simpan Data','form')
            return false;
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
