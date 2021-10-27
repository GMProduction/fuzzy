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


        <div class="row">
            <div class="col-8">
                <div class="table-container">


                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Data Dudi</h5>
                    </div>


                    <table class="table table-striped table-bordered ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Dudi</th>
                                <th>Alamat</th>
                                <th>Foto</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tr>
                            <td>1</td>
                            <td>Teknopark</td>
                            <td>Jl. Ontorejo 8 Serengan Serengan</td>
                            <td>
                                <img src="https://seeklogo.com/images/T/technopark-casablanca-logo-99DC554EBB-seeklogo.com.png"
                                    style=" height: 100px; object-fit: cover" />
                            </td>
                            <td style="width: 150px">
                                <button type="button" class="btn btn-success btn-sm" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Pilih</button>

                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a class="dropdown-item" href="#">Pilihan 1</a></li>
                                    <li><a class="dropdown-item" href="#">Pilihan 2</a></li>
                                    <li><a class="dropdown-item" href="#">Pilihan 3</a></li>
                                </ul>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
            <div class="col-4">
                <div class="table-container mb-3" style="position: relative">


                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Profil</h5>
                    </div>


                    <img src="https://img.tek.id/img/content/2019/10/04/21135/begini-gambaran-proses-syuting-avatar-2-OUv6EI6mLH.jpg"
                        style="width: 100px; height: 100px; border-radius: 100px; object-fit: cover; margin-left: auto; margin-right: auto; display: flex; justify-content: center" />

                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control" disabled id="nim" value="{{$data->siswa->nim}}">
                    </div>

                    <div class="mb-3">
                        <label for="nim" class="form-label">Nama</label>
                        <input type="text" class="form-control" disabled id="nama" value="{{$data->siswa->nama}}">
                    </div>

                    <div class="mb-3">
                        <label for="nim" class="form-label">Alamat</label>
                        <input type="text" class="form-control" disabled id="alamat" value="{{$data->siswa->alamat}}">
                    </div>
                </div>

                <div class="table-container">


                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Pilihan Dudi</h5>
                    </div>

                    <div class="mb-3">
                        <label for="nim" class="form-label">Pilihan Dudi 1</label>
                        <input type="text" class="form-control" disabled id="dudi1">
                    </div>

                    <div class="mb-3">
                        <label for="nim" class="form-label">Pilihan Dudi 2</label>
                        <input type="text" class="form-control" disabled id="dudi2">
                    </div>

                    <div class="mb-3">
                        <label for="nim" class="form-label">Pilihan Dudi 3</label>
                        <input type="text" class="form-control" disabled id="dudi3">
                    </div>

                </div>
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
                            <form>


                                <div class="mb-3">
                                    <label for="namadudi" class="form-label">Nama Dudi</label>
                                    <input type="text" required class="form-control" id="namadudi">
                                </div>

                                <div class="form-group">
                                    <label for="alamat">Alamat</label>
                                    <textarea class="form-control" id="alamat" rows="3"></textarea>
                                </div>


                                <div class="mt-3 mb-2">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input class="form-control" type="file" id="foto">
                                </div>

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
        $(document).ready(function() {

        })

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
