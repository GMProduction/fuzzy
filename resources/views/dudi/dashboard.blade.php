@extends('dudi.base')

@section('title')
    Dashboard
@endsection

@section('content')

    @if (\Illuminate\Support\Facades\Session::has('success'))
        <script>
            swal("Berhasil!", "Berhasil Menambah data!", "success");
        </script>
    @endif

    

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#statusCari').val('{{ request('status') }}')
        })
        var idPesanan;
        $(document).on('click', '#detailData', function() {
            idPesanan = $(this).data('id');
            getDetail(idPesanan);
            $('#detail1').modal('show');
        })

        $(document).on('change', '#statusCari', function() {
            document.getElementById('formCari').submit();
        })

        function getDetail() {
            $.get('/admin/pesanan/' + idPesanan, function(data) {
                console.log(data);
                $('#dNamaPelanggan').html(data['get_pelanggan']['nama'])
                $('#dChat').attr('href', 'https://wa.me/' + data['get_pelanggan']['no_hp'])
                $('#dAlamatPengirimanKota').html(data['get_expedisi']['nama_kota'] + ' - ' + data['get_expedisi'][
                    'nama_propinsi'
                ])
                $('#dAlamatPengiriman').html(data['alamat_pengiriman'])
                $('#dtanggalPesanan').html(moment(data['tanggal_pesanan']).format('DD MMMM YYYY'))
                var biaya = parseInt(data['total_harga'] - data['biaya_pengiriman']);
                $('#dBiaya').html(biaya.toLocaleString())
                $('#dOngkir').html(data['biaya_pengiriman'].toLocaleString())
                $('#dTotal').html(data['total_harga'].toLocaleString())
                $('#dBuktiTransfer').attr('href', data['url_pembayaran'])
                $('#dBuktiTransfer img').attr('src', data['url_pembayaran'])
                $('#dExpedisi').html(data['get_expedisi']['nama'].toUpperCase() + ' ( ' + data['get_expedisi'][
                    'service'
                ] + ' )')
                $('#dEstimasi').html(data['get_expedisi']['estimasi'] + ' Hari')
                var status = data['status_pesanan'];
                var txtStatus = 'Menunggu Pembayaran';
                $('#btnKonfirmasi').addClass('d-none')
                $('#btnKirim').addClass('d-none')
                $('#dAlasan').html('')
                if (status === 1) {
                    $('#btnKonfirmasi').removeClass('d-none')
                    txtStatus = 'Menunggu Konfirmasi'
                } else if (status === 2) {
                    $('#btnKirim').removeClass('d-none')
                    txtStatus = 'Dikemas'
                } else if (status === 3) {
                    txtStatus = 'Dikirim'
                    if (data['get_retur'] && data['get_retur']['status'] === 0) {
                        txtStatus = 'Minta Retur'
                        $('#dAlasan').html(data['get_retur']['alasan'])
                    }
                } else if (status === 4) {
                    txtStatus = 'Selesai'
                } else if (status === 5) {
                    txtStatus = 'Dikembalikan'
                    $('#dAlasan').html(data['get_retur']['alasan'])
                }

                $('#dStatus').html(txtStatus)

                var tabel = $('#tabelDetail');
                tabel.empty();
                $.each(data['get_keranjang'], function(key, value) {
                    console.log(value['get_produk']['get_image'])
                    var foto = value['get_produk']['get_image'].length > 0 ? value['get_produk'][
                        'get_image'][0]['url_foto'] : '/static-image/noimage.jpg';
                    tabel.append('<tr>' +
                        '<td>' + parseInt(key + 1) + '</td>' +
                        '<td><img src="' + foto + '" height="50"/></td>' +
                        '<td>' + value['get_produk']['nama_produk'] + '</td>' +
                        '<td>' + value['qty'] + '</td>' +
                        '<td>' + value['keterangan'] + '</td>' +
                        '<td>' + value['total_harga'].toLocaleString() + '</td>' +
                        '</tr>')
                })
            })
        }

        function saveKonfirmasi(a) {
            var title = 'Tolak Pembayaran'
            if (a === 2) {
                title = 'Terima Pembayaran'
            } else if (a === 3) {
                title = 'Kirim Pesanan'
            }
            var form_data = {
                'status': a,
                '_token': '{{ csrf_token() }}'
            };
            saveDataObject(title, form_data, '/admin/pesanan/' + idPesanan, getDetail)
            return false;

        }
    </script>

@endsection
