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
                <h5>Data Dudi</h5>
                <button type="button" class="btn btn-primary btn-sm" id="addData">Tambah Data
                </button>
            </div>


            <table class="table table-striped table-bordered ">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Dudi</th>
                    <th>Email</th>
                    <th>Alamat</th>
                    <th>Foto</th>
                    <th>Action</th>
                </tr>
                </thead>

                @forelse($data as $key => $d)
                    <tr>
                        <td>{{$data->firstItem() + $key}}</td>
                        <td>{{$d->dudi->nama}}</td>
                        <td>{{$d->username}}</td>
                        <td>{{$d->dudi->alamat}}</td>
                        <td class="text-center">
                            <img src="{{asset($d->dudi->foto)}}" onerror="this.onerror=null; this.src='{{asset('/images/nouser.png')}}' "
                                 style="width: 75px; height: 100px; object-fit: cover"/>
                        </td>
                        <td style="width: 150px">
                            <button type="button" class="btn btn-success btn-sm" id="editData" data-alamat="{{$d->dudi->alamat}}" data-email="{{$d->username}}" data-id="{{$d->id}}" data-nama="{{$d->dudi->nama}}" >Ubah
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapus('id', 'nama') ">hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
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
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form" onsubmit="return save()">
                                @csrf
                                <input type="hidden" name="id" id="id">
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Dudi</label>
                                    <input type="text" required class="form-control" id="nama" name="nama">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" required class="form-control" id="email" name="username">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" required class="form-control" id="password" name="password">
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                                </div>


{{--                                <div class="mt-3 mb-2">--}}
{{--                                    <label for="foto" class="form-label">Foto</label>--}}
{{--                                    <input class="form-control" type="file" id="foto">--}}
{{--                                </div>--}}

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

        $(document).on('click','#addData, #editData', function () {
            $('#modal #id').val($(this).data('id'));
            $('#modal #nama').val($(this).data('nama'));
            $('#modal #email').val($(this).data('email'));
            $('#modal #alamat').val($(this).data('alamat'));
            $('#modal #password').val('');
            if ($(this).data('id')){
                $('#modal #password').val('********');
            }
            $('#modal').modal('show');
        })

        function save() {
            saveData('Simpan Data','form')
            return false;
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
