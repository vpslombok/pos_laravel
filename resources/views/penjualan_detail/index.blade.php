@extends('layouts.master')

@section('title')
Transaksi Penjualan
@endsection

@push('css')
<style>
    body {
        font-family: 'Arial', sans-serif;
    }

    .tampil-bayar {
        font-size: 5em;
        text-align: center;
        height: 100px;
    }

    .tampil-terbilang {
        padding: 5px;
        font-size: medium;
        background: #f0f0f0;
    }

    .table-penjualan tbody tr:last-child {
        display: none;
    }

    /* css cetak ulang nota */
    .flexbox {
        text-align: right;
        /* supya ke atas sedikit */
        margin-top: -50px;
    }


    @media(max-width: 768px) {
        .tampil-bayar {
            font-size: 10em;
            height: 70px;
            padding-top: 5px;
        }
    }
</style>
@endpush

@section('breadcrumb')
@parent
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <div class="col" style="color: black;"><strong>Kasir:</strong> {{ auth()->user()->name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow" hx-get="{{ route('transaksi.updateNomorNota') }}" hx-trigger="every 1s" hx-target="#nomor-nota">
                            <div class="inner">
                                <div class="col" id="nomorNota" style="color: black;">
                                    <strong>Faktur:</strong>
                                    <span id="nomor-nota"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow">
                            <div class="inner">
                                <div class="col" style="color: black;"><strong>Tanggal:</strong> <span id="waktu-sekarang"></span></div>
                            </div>
                        </div>
                    </div>
                    <script>
                        setInterval(function() {
                            var waktuSekarang = new Date(new Date().toLocaleString("en-US", {
                                timeZone: "{{ config('app.timezone') }}"
                            }));
                            var tanggal = waktuSekarang.getDate().toString().padStart(2, '0');
                            var bulan = (waktuSekarang.getMonth() + 1).toString().padStart(2, '0');
                            var tahun = waktuSekarang.getFullYear();
                            var jam = waktuSekarang.getHours().toString().padStart(2, '0');
                            var menit = waktuSekarang.getMinutes().toString().padStart(2, '0');
                            var detik = waktuSekarang.getSeconds().toString().padStart(2, '0');
                            document.getElementById('waktu-sekarang').innerHTML = tanggal + '-' + bulan + '-' + tahun + ' ' + jam + ':' + menit + ':' + detik;
                        }, 1000);
                    </script>
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box bg-yellow" hx-get="{{ route('transaksi.datamember') }}" hx-trigger="every 1s" hx-target="#member">
                            <div class="inner">
                                <div class="data-member" style="color: black;"><strong>member:</strong>
                                    <span id="member"></span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <form class="form-produk" hx-post="{{ route('transaksi.postMember') }}" hx-trigger="mouseenter" hx-target="#kode_member">
                @csrf
                <div class="form-group row">
                    <div class="col-lg-8 col-md-1 col-sm-6 col-xs-12">
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{ $id_penjualan }}">
                                <input type="hidden" name="id_produk" id="id_produk">
                                <input type="text" class="form-control" name="kode_produk" id="kode_produk" placeholder="Input PLU/Barcode">
                                <input type="hidden" name="barcode" id="barcode">
                                <span class="input-group-btn">
                                    <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-8">
                        <div class="col-lg-5">
                            <div class="input-group">
                                <input type="text" class="form-control" id="kode_member" name="kode_member" placeholder="masukkan nomor member" value="{{ $memberSelected->telepon}}">
                                <input type="hidden" name="member" id="member" value="">
                                <span class="input-group-btn">
                                    <button onclick="tampilMember()" class="btn btn-info btn-flat" type="button"><i class="fa fa-arrow-right"></i></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Tombol untuk membuka modal -->
            <div class="flexbox" style="border-radius: 5px;">
                <span class="cetak-ulang-nota" style="margin-right: 10px;">
                    <button class="btn btn-primary btn-sm btn-flat " data-toggle="modal" data-target="#notaModal" style="border-radius: 5px;">
                        <i class="fa fa-print"></i> Cetak Ulang Nota
                    </button>
                </span>
                <span class="layar-penuh">
                    <button class="btn btn-primary btn-sm btn-flat" onclick="toggleFullScreen()" style="border-radius: 5px;">
                        <i class="fa fa-expand"></i> Layar Besar
                    </button>
                </span>

                <script>
                    function toggleFullScreen() {
                        var elem = document.documentElement;
                        var button = document.querySelector('.layar-penuh button');

                        if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
                            if (document.exitFullscreen) {
                                document.exitFullscreen();
                            } else if (document.webkitExitFullscreen) {
                                document.webkitExitFullscreen();
                            } else if (document.mozCancelFullScreen) {
                                document.mozCancelFullScreen();
                            } else if (document.msExitFullscreen) {
                                document.msExitFullscreen();
                            }
                        } else {
                            if (elem.requestFullscreen) {
                                elem.requestFullscreen();
                            } else if (elem.mozRequestFullScreen) {
                                elem.mozRequestFullScreen();
                            } else if (elem.webkitRequestFullscreen) {
                                elem.webkitRequestFullscreen();
                            } else if (elem.msRequestFullscreen) {
                                elem.msRequestFullscreen();
                            }
                        }
                    }

                    function updateButton() {
                        var button = document.querySelector('.layar-penuh button');
                        if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
                            button.innerHTML = '<i class="fa fa-compress"></i> Layar Kecil';
                        } else {
                            button.innerHTML = '<i class="fa fa-expand"></i> Layar Besar';
                        }
                    }

                    document.addEventListener('fullscreenchange', updateButton);
                    document.addEventListener('webkitfullscreenchange', updateButton);
                    document.addEventListener('mozfullscreenchange', updateButton);
                    document.addEventListener('MSFullscreenChange', updateButton);
                </script>


                <!-- Modal cetak ulang nota -->
                <div class="modal fade" id="notaModal" tabindex="-1" role="dialog" aria-labelledby="notaModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <!-- respons ajax-->
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form hx-post="{{ route('transaksi.cetakNota') }}" hx-target="#nomor_nota" hx-trigger="submit">
                                    @csrf
                                    <div class="form-group">
                                        <label for="notaNumber">Nomor Nota:</label>
                                        <input type="text" class="form-control" id="nomor_nota" name="nomor_nota" placeholder="Masukkan nomor nota">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary" id="cetakButton" onclick="notaKecil(`{{ route('transaksi.nota_kecil') }}`, 'Nota Kecil')" disabled>Cetak</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const notaInput = document.getElementById('nomor_nota');
                        const cetakButton = document.getElementById('cetakButton');

                        notaInput.addEventListener('input', function() {
                            const nomorNota = notaInput.value.trim();
                            if (nomorNota === '' || nomorNota.length !== 8) {
                                cetakButton.disabled = true;
                            } else {
                                cetakButton.disabled = false;
                            }
                        });

                        // Initialize button state
                        cetakButton.disabled = true; // Menunggu input
                    });
                </script>




                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th width="5%">plu</th>
                        <th>barcode</th>
                        <th>Nama</th>
                        <th width="10%">Harga</th>
                        <th width="10%">Jumlah</th>
                        <th>Diskon</th>
                        <th>Subtotal</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="tampil-bayar bg-black"></div>
                        <div class="tampil-terbilang bg-black"></div>
                    </div>




                    <div class="col-lg-4">
                        <form action="{{ route('transaksi.simpan') }}" class="form-penjualan" method="post">
                            @csrf
                            <input type="hidden" name="id_penjualan" value="{{ $id_penjualan }}">
                            <input type="hidden" name="total" id="total">
                            <input type="hidden" name="total_item" id="total_item">
                            <input type="hidden" name="bayar" id="bayar">
                            <input type="hidden" name="id_member" id="id_member" value="{{ $memberSelected->id_member }}">

                            <div class="form-group row">
                                <label for="totalrp" class="col-lg-2 control-label">Total</label>
                                <div class="col-lg-8">
                                    <input type="text" id="totalrp" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="diskon" class="col-lg-2 control-label">Diskon Member</label>
                                <div class="col-lg-8">
                                    <input type="number" name="diskon" id="diskon" class="form-control" value="{{ ! empty($memberSelected->id_member) ? $diskon : 0 }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total_diskon" class="col-lg-2 control-label">Total Diskon</label>
                                <div class="col-lg-8">
                                    <input type="text" id="total_diskon" name="total_diskon" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="bayar" class="col-lg-2 control-label">Total Belanja</label>
                                <div class="col-lg-8">
                                    <input type="text" id="bayarrp" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="diterima" class="col-lg-2 control-label">Tunai</label>
                                <div class="col-lg-8">
                                    <input type="number" id="diterima" class="form-control" name="diterima" value="{{ $penjualan->diterima ?? 0 }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="kembali" class="col-lg-2 control-label">Kembalian</label>
                                <div class="col-lg-8">
                                    <input type="text" id="kembali" name="kembali" class="form-control" value="0" readonly>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="box-footer">
                <button type="submit" class="btn btn-primary btn-sm btn-flat pull-right btn-simpan"><i class="fa fa-floppy-o"></i> Simpan Transaksi</button>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan_detail.produk')
@includeIf('penjualan_detail.member')
@endsection

@push('scripts')
<script>
    let table, table2;

    $(function() {
        $('body').addClass('sidebar-collapse');

        table = $('.table-penjualan').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: `{{ route('transaksi.data', $id_penjualan) }}`,
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'kode_produk'
                    },
                    {
                        data: 'barcode'
                    },
                    {
                        data: 'nama_produk'
                    },
                    {
                        data: 'harga_jual'
                    },
                    {
                        data: 'jumlah'
                    },
                    {
                        data: 'diskon'
                    },
                    {
                        data: 'subtotal'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    },
                ],
                dom: 'Brt',
                bSort: false,
                paginate: false

            })
            .on('draw.dt', function() {
                loadForm($('#diskon').val());
                setTimeout(() => {
                    $('#diterima').trigger('input');
                }, 3000);
            });
        table2 = $('.table-produk').DataTable();


        $(document).on('change', '.quantity', function() {
            let id = $(this).data('id');
            let jumlah = parseInt($(this).val());
            let stok = parseInt($(this).data('stok'));
            let nama_produk = $(this).data('nama-produk');

            // console.log(jumlah)
            // console.log(stok)
            // console.log(id)

            if (jumlah > stok) {
                Swal.fire({
                    icon: 'warning',
                    title: `${nama_produk}`,
                    text: `Stok tersedia hanya ${stok}`,
                });
                // Mengembalikan jumlah ke stok maksimum yang tersedia
                $(`.quantity[data-id="${id}"]`).val(stok).trigger('change');
                return;
            }

            if (jumlah < 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Mohon Maaf...',
                    text: 'Jumlah produk minimal 1',
                    width: 400,
                });
                $(`.quantity[data-id="${id}"]`).val(1).trigger('change');
                return;
            }


            // Jika jumlah valid, kirim permintaan untuk memperbarui jumlah produk
            $.post(`{{ url('/transaksi') }}/${id}`, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'put',
                    'jumlah': jumlah,
                })
                .done(response => {
                    $(this).on('mouseout', function() {
                        table.ajax.reload(() => loadForm($('#diskon').val()));
                    });
                })

                .fail(errors => {
                    // Menampilkan pesan error jika terjadi kesalahan
                    Swal.fire({
                        icon: 'error',
                        title: 'Maaf...',
                        text: 'Quantity Tidak Boleh kosong',
                    });
                    $(`.quantity[data-id="${id}"]`).val(1).trigger('change');
                    return;
                })
            if (jumlah > stok) {
                Swal.fire({
                    icon: 'warning',
                    title: `${nama_produk}`,
                    text: `Stok tersedia hanya ${stok}`,
                });
                // Mengembalikan jumlah ke stok maksimum yang tersedia
                $(`.quantity[data-id="${id}"]`).val(stok).trigger('change');
                return;
            }

        });


        $(document).on('input', '#diskon', function() {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }

            loadForm($(this).val());
        });

        $('#diterima').on('input', function() {
            if ($(this).val() == "") {
                $(this).val(0).select();
            }


            loadForm($('#diskon').val(), $(this).val());
        }).focus(function() {
            $(this).select();
        });

        $('.btn-simpan').on('click', function() {
            let diterima = parseFloat($('#diterima').val());
            let bayar = parseFloat($('#bayar').val());
            let total = parseFloat($('#total').val());


            if (diterima < bayar) {
                Swal.fire({
                    icon: 'error',
                    title: 'Mohon Maaf...',
                    text: 'Jumlah yang diterima kurang dari total pembayaran!',
                });
                return; // Menghentikan proses submit jika jumlah yang diterima kurang dari total pembayaran
            }
            // jika total bayar minus maka 
            if (bayar == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Mohon Maaf...',
                    text: 'Penjualan Masih Kosong!',
                });
                return; // Menghentikan proses submit jika total pembayaran 0
            }

            if (total == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Mohon Maaf...',
                    text: 'Penjualan Masih Kosong!',
                });
                return; // Menghentikan proses submit jika total pembayaran 0
            }

            $('.form-penjualan').submit();
            // sweet alert berhasil simpan data penjualan
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data penjualan berhasil disimpan',
                timer: 2000,
            });
        });

    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode, stok) {
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        $('#stok').val(stok);
        // $('#diskon').val('{{ $diskon }}');
        // loadForm($('#diskon').val());
        hideProduk();
        tambahProduk();
    }
    $(document).ready(function() {
        $('#kode_produk').keypress(function(event) {
            // Jika tombol Enter ditekan (keyCode 13)
            if (event.keyCode === 13) {
                event.preventDefault(); // Menghentikan aksi default form submission

                // Panggil fungsi tambahProduk()
                tambahProduk();
            }
        });
    });


    function tambahProduk() {

        // Melakukan AJAX untuk menambahkan produk
        $.post(`{{ route('transaksi.store') }}`, $('.form-produk').serialize())
            .done(response => {
                $('#kode_produk').val(''); // Kosongkan input kode produk setelah berhasil tambah
                $('#kode_produk').focus(); // Fokuskan kembali ke input kode produk
                table.ajax.reload(() => loadForm($('#diskon').val())); // Reload tabel setelah tambah produk
            })
            .fail(errors => {
                var error = errors.responseJSON; // Mengambil pesan error dari response JSON
                Swal.fire({
                    icon: 'error',
                    title: 'Mohon Maaf...',
                    text: error, // Menampilkan pesan error dalam SweetAlert
                }).then(() => {
                    $('#kode_produk').focus(); // Fokuskan kembali ke input kode produk setelah menutup SweetAlert
                    $('#kode_produk').val(''); // Kosongkan input kode produk setelah berhasil tambah
                    table.ajax.reload(() => loadForm($('#diskon').val())); // Reload tabel setelah tambah produk
                });
                if (stok < 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Stok Habis',
                        text: 'Maaf, stok produk ini telah habis.',
                    });
                }
            });
    }


    function cetakulangnota() {
        $('#modal-nota').modal('show');
    }



    function tampilMember() {
        $('#modal-member').modal('show');
    }

    function pilihMember(id, kode, nama) {
        $('#id_member').val(id);
        $('#kode_member').val(kode);
        $('#nama_member').text(nama); // Menampilkan nama member
        $('#diterima').val(0).focus().select();
        hideMember();
    }



    function hideMember() {
        $('#modal-member').modal('hide');
    }


    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload(() => loadForm($('#diskon').val()));
                })
                .fail((errors) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tidak dapat menghapus data',
                    });
                    return;
                });
        }
    }

    function loadForm(diskon = 0, diterima = 0) {
        $('#total').val($('.total').text());
        $('#total_diskon').val($('.total_diskon').text());
        $('#total_item').val($('.total_item').text());

        $.get(`{{ url('/transaksi/loadform') }}/${diskon}/${$('.total').text()}/${diterima}/${$('.total_diskon').text()}`)
            .done(response => {
                $('#totalrp').val('Rp. ' + response.totalrp);
                $('#total_diskon').val(response.total_diskon);
                $('#bayarrp').val('Rp. ' + response.bayarrp);
                $('#bayar').val(response.bayar);
                $('.tampil-bayar').text('Bayar: Rp. ' + response.bayarrp);
                $('.tampil-terbilang').text(response.terbilang);

                $('#kembali').val('Rp.' + response.kembalirp);
                if ($('#diterima').val() != 0) {
                    $('.tampil-bayar').text('Kembalian: Rp. ' + response.kembalirp);
                    $('.tampil-terbilang').text(response.kembali_terbilang);
                }
            })
            .fail(errors => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Tidak dapat mengambil data',
                });
                return;
            })
    }
</script>
<script>
    // tambahkan untuk delete cookie innerHeight terlebih dahulu
    document.cookie = "innerHeight=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

    function notaKecil(url, title) {
        popupCenter(url, title, 625, 500);
    }

    function notaBesar(url, title) {
        popupCenter(url, title, 900, 675);
    }

    function popupCenter(url, title, w, h) {
        const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft
        const top = (height - h) / 2 / systemZoom + dualScreenTop
        const newWindow = window.open(url, title,
            `
            scrollbars=yes,
            width  = ${w / systemZoom}, 
            height = ${h / systemZoom}, 
            top    = ${top}, 
            left   = ${left}
        `
        );

        if (window.focus) newWindow.focus();
    }
</script>
@endpush