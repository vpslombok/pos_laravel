@extends('layouts.master')

@section('title')
Laporan Penjualan Produk Per Tanggal
@endsection

@section('breadcrumb')
@parent
<li class="active">Laporan Penjualan Produk</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="updatePeriode()" class="btn btn-info btn-xs btn-flat"><i class="fa fa-calendar"></i> Pilih Periode</button>
                <a href="{{ route('laporan.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank" class="btn btn-success btn-xs btn-flat"><i class="fa fa-file-pdf-o"></i> Export PDF</a>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered table-penjualan" id="tablePenjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>No. Nota</th>
                        <th>Nama Produk</th>
                        <th>Jumlah Terjual</th>
                        <th>Total Harga</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function updatePeriode() {
        // Implementasi untuk memilih periode tanggal
        // Placeholder untuk fungsi update periode
    }

    $(document).ready(function() {
        var table = $('#tablePenjualan').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("penjualan.data", ["tanggalAwal" => $tanggalAwal, "tanggalAkhir" => $tanggalAkhir]) }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'kasir', name: 'kasir' },
                { data: 'no_nota', name: 'no_nota' },
                { data: 'nama_produk', name: 'nama_produk' },
                { data: 'jumlah_terjual', name: 'jumlah_terjual' },
                { data: 'total_harga', name: 'total_harga' }
            ]
        });
    });
</script>