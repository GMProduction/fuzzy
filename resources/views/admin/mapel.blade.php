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
                    <th>Indicator Nilai</th>
                    <th>Action</th>
                </tr>
                </thead>
                @forelse($data as $key => $d)
                    <tr>
                        <td>{{$data->firstItem() + $key}}</td>
                        <td>{{$d->nama}}</td>
                        <td>{{$d->alias}}</td>
                        <td class="text-center"><a href="#" class="btn btn-primary btn-sm btn-indicator"
                                                   data-mapel="{{$d->id}}">{{ count($d->indicator) > 0 ? 'Detail' : 'Buat Indikator'  }}</a>
                        </td>
                        <td style="width: 150px">
                            <a type="button" class="btn btn-success btn-sm" id="editData" data-id="{{$d->id}}"
                               data-nama="{{$d->nama}}" data-alias="{{$d->alias}}">Ubah</a>
                            <button type="button" class="btn btn-danger btn-sm" onclick="hapus('id', 'nama') ">hapus
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

        <div class="modal fade" id="modalIndicator" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Indikator Batas Nilai Mata Pelajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="">Batas Mata Pelajaran <span id="title-indicator"></span></p>
                        <input type="hidden" id="idMapel" name="idMapel">
                        <div class="mb-3">
                            <span class="mb-3" style="font-weight: bold">Indicator Rendah</span>
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="rendah-bawah" class="form-label">Batas Bawah</label>
                                        <input type="number" class="form-control" id="rendah-bawah"
                                               name="rendah-bawah" value="0" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="rendah-tengah" class="form-label">Batas Tengah</label>
                                        <input type="number" class="form-control" id="rendah-tengah"
                                               name="rendah-tengah" value="0" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="rendah-atas" class="form-label">Batas Atas</label>
                                        <input type="number" class="form-control" id="rendah-atas"
                                               name="rendah-atas" value="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <span class="mb-3" style="font-weight: bold">Indicator Cukup</span>
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="cukup-bawah" class="form-label">Batas Bawah</label>
                                        <input type="number" class="form-control" id="cukup-bawah"
                                               name="cukup-bawah" value="0" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="cukup-tengah" class="form-label">Batas Tengah</label>
                                        <input type="number" class="form-control" id="cukup-tengah"
                                               name="cukup-tengah" value="0" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="cukup-atas" class="form-label">Batas Atas</label>
                                        <input type="number" class="form-control" id="cukup-atas" name="cukup-atas"
                                               value="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <span class="mb-3" style="font-weight: bold">Indicator Tinggi</span>
                            <div class="row">
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="tinggi-bawah" class="form-label">Batas Bawah</label>
                                        <input type="number" class="form-control" id="tinggi-bawah"
                                               name="tinggi-bawah" value="0" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="tinggi-tengah" class="form-label">Batas Tengah</label>
                                        <input type="number" class="form-control" id="tinggi-tengah"
                                               name="tinggi-tengah" value="0" required>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="tinggi-atas" class="form-label">Batas Atas</label>
                                        <input type="number" class="form-control" id="tinggi-atas"
                                               name="tinggi-atas" value="0" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4"></div>
                        <button class="btn btn-primary btn-save-indicator">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script>
        $(document).ready(function () {

            $('.btn-indicator').on('click', function () {
                let mapel = this.dataset.mapel;
                getIndicatorMapel(mapel);

            });

            $('.btn-save-indicator').on('click', function () {
                postIndicator();
            });


        });

        async function getIndicatorMapel(id) {
            try {
                let response = await $.get('/admin/mapel/indicator/' + id);
                let data = response['data'];
                if (data.length > 0) {
                    let rendah = data.find(v => v.indikator === 'rendah');
                    let cukup = data.find(v => v.indikator === 'cukup');
                    let tinggi = data.find(v => v.indikator === 'tinggi');
                    if (rendah !== undefined) {
                        let v_rendah_bawah = rendah['bawah'];
                        let v_rendah_tengah = rendah['tengah'];
                        let v_rendah_atas = rendah['atas'];
                        $('#rendah-bawah').val(v_rendah_bawah);
                        $('#rendah-tengah').val(v_rendah_tengah);
                        $('#rendah-atas').val(v_rendah_atas);
                    }

                    if (cukup !== undefined) {
                        let v_cukup_bawah = cukup['bawah'];
                        let v_cukup_tengah = cukup['tengah'];
                        let v_cukup_atas = cukup['atas'];
                        $('#cukup-bawah').val(v_cukup_bawah);
                        $('#cukup-tengah').val(v_cukup_tengah);
                        $('#cukup-atas').val(v_cukup_atas);
                    }

                    if (tinggi !== undefined) {
                        let v_tinggi_bawah = tinggi['bawah'];
                        let v_tinggi_tengah = tinggi['tengah'];
                        let v_tinggi_atas = tinggi['atas'];
                        $('#tinggi-bawah').val(v_tinggi_bawah);
                        $('#tinggi-tengah').val(v_tinggi_tengah);
                        $('#tinggi-atas').val(v_tinggi_atas);
                    }
                }
                $('#idMapel').val(id);
                $('#modalIndicator').modal('show');
            } catch (e) {
                console.log(e);
            }
        }

        async function postIndicator() {
            let id = $('#idMapel').val();
            try {
                let rendah_bawah = $('#rendah-bawah').val();
                let rendah_tengah = $('#rendah-tengah').val();
                let rendah_atas = $('#rendah-atas').val();

                let cukup_bawah = $('#cukup-bawah').val();
                let cukup_tengah = $('#cukup-tengah').val();
                let cukup_atas = $('#cukup-atas').val();

                let tinggi_bawah = $('#tinggi-bawah').val();
                let tinggi_tengah = $('#tinggi-tengah').val();
                let tinggi_atas = $('#tinggi-atas').val();
                let response = await $.post('/admin/mapel/indicator/' + id + '/create', {
                    _token: '{{ csrf_token() }}', id,
                    rendah_bawah, rendah_tengah, rendah_atas,
                    cukup_bawah, cukup_tengah, cukup_atas,
                    tinggi_bawah, tinggi_tengah, tinggi_atas
                });
                console.log(response);
            } catch (e) {
                console.log(e)
            }
        }

        $(document).on('click', '#addData, #editData', function () {
            $('#modal #id').val($(this).data('id'))
            $('#modal #nama').val($(this).data('nama'))
            $('#modal #alias').val($(this).data('alias'))
            $('#modal').modal('show')

        });

        function save() {
            saveData('Simpan Data', 'form')
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
