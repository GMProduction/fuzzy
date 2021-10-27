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
                <h5>Data Siswa</h5>
                <button type="button ms-auto" class="btn btn-primary btn-sm" id="addData">Tambah Data
                </button>
            </div>


            <table class="table table-striped table-bordered ">
                <thead>
                <th>#</th>
                <th>NIM</th>
                <th>Nama Siswa</th>
                <th>Alamat</th>
                <th>No. HP</th>
                <th>Foto</th>
                <th>Action</th>
                </thead>
                @forelse($data as $key => $d)
                    <tr>
                        <td> {{$data->firstItem() + $key}}</td>
                        <td>{{$d->username}}</td>
                        <td>{{$d->siswa->nama}}</td>
                        <td>{{$d->siswa->alamat}}</td>
                        <td>{{$d->siswa->hp}}</td>
                        <td class="text-center">
                            <img src="{{asset($d->siswa->foto)}}" onerror="this.onerror=null; this.src='{{asset('/images/nouser.png')}}' "
                                 style="width: 75px; height: 100px; object-fit: cover"/>
                        </td>
                        <td style="width: 150px">
                            <a type="button" class="btn btn-success btn-sm" id="editData" data-hp="{{$d->siswa->hp}}" data-alamat="{{$d->siswa->alamat}}" data-nama="{{$d->siswa->nama}}" data-id="{{$d->id}}" data-user="{{$d->username}}">Ubah
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapus('id', 'nama') ">hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                @endforelse

            </table>
            <div class="d-flex justify-content-end">
                {{$data->links()}}
            </div>
        </div>


        <div>


            <!-- Modal Tambah-->
            <div class="modal fade" id="tambahsiswa" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Tambah Siswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form" enctype="multipart/form-data" onsubmit="return save()">
                                @csrf
                                <input type="hidden" id="id" name="id">
                                <div class="mb-3">
                                    <label for="nim" class="form-label">NIM</label>
                                    <input type="text" class="form-control" required id="username" name="username">
                                </div>

                                <div class="mb-3">
                                    <label for="namasiswa" class="form-label">Nama Siswa</label>
                                    <input type="text" required class="form-control" id="nama" name="nama">
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" rows="3" name="alamat"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="nphp" class="form-label">no. Hp</label>
                                    <input type="number" required class="form-control" id="hp" name="hp">
                                </div>

{{--                                <div class="mt-3 mb-2">--}}
{{--                                    <label for="foto" class="form-label">Foto</label>--}}
{{--                                    <input class="form-control" type="file" id="foto" name="foto">--}}
{{--                                </div>--}}

                                {{-- <div class="mb-3">
                                    <label for="kategori" class="form-label">Kategori</label>
                                    <div class="d-flex">
                                        <select class="form-select" aria-label="Default select example" name="idguru">
                                            <option selected>Mata Pelajaran</option>
                                            <option value="1">Erfin</option>
                                            <option value="2">Joko A</option>
                                            <option value="3">Joko B</option>
                                        </select>
                                        <a class="btn btn-primary">+</a>
                                    </div>
                                </div> --}}

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

        $(document).on('click','#editData, #addData', function () {
            $('#tambahsiswa #id').val($(this).data('id'))
            $('#tambahsiswa #alamat').val($(this).data('alamat'))
            $('#tambahsiswa #hp').val($(this).data('hp'))
            $('#tambahsiswa #nama').val($(this).data('nama'))
            $('#tambahsiswa #username').val($(this).data('user'))
            $('#tambahsiswa').modal('show')
        })

        function save() {
            saveData('Simpan data', 'form')
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
