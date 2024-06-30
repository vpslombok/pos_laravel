@extends('layouts.master')

@section('title')
    Data Presensi
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Data Presensi</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nik</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Pulang</th>
                            <th>Total Jam</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($presensis as $presensi)
                            <tr>
                                <td>{{ $presensi['no'] }}</td>
                                <td>{{ $presensi['nik'] }}</td>
                                <td>{{ $presensi['nama'] }}</td>
                                <td>{{ $presensi['tanggal'] }}</td>
                                <td>{{ $presensi['waktu_masuk'] }}</td>
                                <td>{{ $presensi['waktu_keluar'] }}</td>
                                <td>{{ $presensi['total_jam'] }}</td>
                                <td>
                                    <a href="{{ route('presensi.edit', $presensi['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="{{ route('presensi.delete', $presensi['id']) }}" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@includeIf('user.form')
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.table').DataTable({
        searching: true
    });
});
</script>
@endpush
