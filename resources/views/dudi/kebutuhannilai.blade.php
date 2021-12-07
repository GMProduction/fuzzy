@extends('dudi.base')

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
                        <h5>Kebutuhan Nilai</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRule">
                            Buat Peraturan Penilaian
                        </button>
                    </div>
                    <form method="POST" id="form" onsubmit="return save()">
                        @csrf
                        @foreach($mapel as $d)
                            <div class="mb-3">
                                <label for="nim" class="form-label">{{$d->nama}} :</label>
                                <input name="mapel[]" hidden value="{{$d->id}}">
                                <input type="text" class="form-control" id="dudi1" name="nilai[]"
                                       value="{{$d->kebutuhan ? $d->kebutuhan->nilai : '0'}}">
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                </div>
            </div>
            <div class="col-4">
                <div class="table-container mb-3" style="position: relative">


                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Profil</h5>
                    </div>


                    <img
                        src="https://img.tek.id/img/content/2019/10/04/21135/begini-gambaran-proses-syuting-avatar-2-OUv6EI6mLH.jpg"
                        style="width: 100px; height: 100px; border-radius: 100px; object-fit: cover; margin-left: auto; margin-right: auto; display: flex; justify-content: center"/>

                    <div class="mb-3">
                        <label for="nim" class="form-label">Email</label>
                        <input type="text" class="form-control" disabled id="nim" value="{{$data->username}}">
                    </div>

                    <div class="mb-3">
                        <label for="nim" class="form-label">Nama</label>
                        <input type="text" class="form-control" disabled id="nama" value="{{$data->dudi->nama}}">
                    </div>

                    <div class="mb-3">
                        <label for="nim" class="form-label">Alamat</label>
                        <input type="text" class="form-control" disabled id="alamat" value="{{$data->dudi->alamat}}">
                    </div>
                </div>


            </div>
        </div>
        <div>
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

        <div class="modal fade" id="modalRule" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach($mapel as $v)
                            <div class="form-check">
                                <input class="form-check-input maple-check" type="checkbox" value="{{ $v->id }}" id="mapel" name="mapel">
                                <label class="form-check-label" for="mapel">
                                    {{ $v->nama }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>

        function elRule()
        {
            // let value = $('input[name=mapel]:checked').val();
            let value = $("input:checkbox:checked").map(function(){
                return $(this).val();
            }).get(); // <----

            let combos = [];
            let indicator = ['rendah', 'cukup', 'tinggi'];
            let val_length = value.length;
            let indicator_length = indicator.length;
            console.log(Math.pow(3, 0));

            let combos2 = [];
            for (let v = 0; v < val_length; v++) {
                combos2.push(Math.pow(indicator_length, v));
            }
            // for ( let i=0; i < value.length; i++){
            //     for (let j=0; j < indicator.length; j++) {
            //         combos.push(value[i] + indicator[j])
            //     }
            // }
            // console.log(combos);
            console.log(combos2);
        }
        $(document).ready(function () {
            $('.btn-rules').on('click', function () {
                $('#exampleModal').modal('show');
            })

            $('.maple-check').on('click', function () {
                elRule();
            })
        });

        function save() {
            saveData('Simpan Data', 'form', null, after)
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
