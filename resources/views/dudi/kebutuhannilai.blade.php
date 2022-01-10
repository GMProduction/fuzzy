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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalRule">
                            Buat Peraturan Penilaian
                        </button>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>Nama</td>
                            <td>Persentase</td>
                            <td>Indikator</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($rules as $rule)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $rule->name }}</td>
                                <td>{{ $rule->percentage }}</td>
                                <td>
                                    @foreach($rule->indicator as $indicator)
                                        <p>{{ $indicator->mapel->nama }} : {{ $indicator->value }}</p>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{--                    <form method="POST" id="form" onsubmit="return save()">--}}
                    {{--                        @csrf--}}
                    {{--                        @foreach($mapel as $d)--}}
                    {{--                            <div class="mb-3">--}}
                    {{--                                <label for="nim" class="form-label">{{$d->nama}} :</label>--}}
                    {{--                                <input name="mapel[]" hidden value="{{$d->id}}">--}}
                    {{--                                <input type="text" class="form-control" id="dudi1" name="nilai[]"--}}
                    {{--                                       value="{{$d->kebutuhan ? $d->kebutuhan->nilai : '0'}}">--}}
                    {{--                            </div>--}}
                    {{--                        @endforeach--}}

                    {{--                        <button type="submit" class="btn btn-success">Simpan</button>--}}
                    {{--                    </form>--}}
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
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @foreach($mapel as $v)
                            <div class="form-check">
                                <input class="form-check-input maple-check" type="checkbox" data-nama="{{ $v->nama }}"
                                       value="{{ $v->id }}"
                                       id="mapel" name="mapel">
                                <label class="form-check-label" for="mapel">
                                    {{ $v->nama }}
                                </label>
                            </div>
                        @endforeach
                        <form action="/dudi/nilai/rule" method="post" id="form-rule">
                            @csrf
                            <div id="combos_rule">

                            </div>
                        </form>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" form="form-rule">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>

        function elRule() {
            // let value = $('input[name=mapel]:checked').val();
            let value = $("input:checkbox:checked").map(function () {
                return $(this).val();
            }).get(); // <----

            let nama = $("input:checkbox:checked").map(function () {
                return $(this).data('nama');
            }).get();
            let combos = [];
            let indicator = ['rendah', 'cukup', 'tinggi'];
            let val_length = value.length;
            let indicator_length = indicator.length;
            let combosLength = Math.pow(indicator_length, val_length);

            let combos2 = [];
            for (let v = 0; v < val_length; v++) {
                combos2.push(Math.pow(indicator_length, v));
            }

            let combos2revert = [];
            for (let c = combos2.length; c > 0; c--) {
                combos2revert.push(combos2[c - 1]);
            }

            let tempResult = [];
            $.each(combos2revert, function (k, v) {
                let tempArray = [];
                for (let j = 0; j < combos2[k]; j++) {
                    $.each(indicator, function (k2, v2) {
                        for (let i = 0; i < v; i++) {
                            tempArray.push(v2);
                        }
                    });
                }
                tempResult.push(tempArray);
            });

            let result = [];
            for (let z = 0; z < combosLength; z++) {
                let tmp = [];
                for (let x = 0; x < tempResult.length; x++) {
                    let obj = {};
                    obj['id'] = value[x];
                    obj['nama'] = nama[x];
                    obj['value'] = tempResult[x][z];
                    tmp.push(obj);
                }
                result.push(tmp);
            }
            let element = $('#combos_rule');
            element.empty();
            $.each(result, function (k, v) {
                element.append(createElem(v, k));
            });
            console.log(tempResult);
            console.log(result);
        }

        function createElem(data, k1) {
            let elem = '';
            $.each(data, function (k, v) {
                elem += '<div class="mr-2" style="margin-right: 10px">' +
                    '<input type="hidden" value="' + v['id'] + '" name="rule-' + k1 + '[]">' +
                    '<label for="rule-' + (k + 1) + '" class="form-label" style="font-size: 12px; font-weight: bold;margin-bottom: 0 ">' + v['nama'] + '</label>\n' +
                    '<input style="font-size: 12px" type="text" class="form-control" id="rule-' + (k + 1) + '" value="' + v['value'] + '" name="nilai-' + k1 + '[]">\n' +
                    '</div>';
            });
            return '<div class="d-flex align-items-center" style="margin-bottom: 10px">' +
                '<p class="mb-0" style="font-size: 14px; font-weight: bold; margin-right: 20px">Rule Ke :' + (k1 + 1) + '</p>' +
                '<div>' +
                '<label for="percentage-' + (k1 + 1) + '" class="form-label" style="font-size: 12px; font-weight: bold; margin-bottom: 0">Persentase</label>' +
                '<select name="persentase[]" style="font-size: 12px;" type="text" class="form-select-sm" id="percentage-' + (k1 + 1) + '" aria-label="Persentase">' +
                '<option value="rendah">Rendah</option>' +
                '<option value="cukup">Cukup</option>' +
                '<option value="tinggi">Tinggi</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '<div class="d-flex mb-1" style="margin-bottom: 10px">' + elem +
                '</div>'
        }

        $(document).ready(function () {
            $('.btn-rules').on('click', function () {
                $('#exampleModal').modal('show');
            });

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
