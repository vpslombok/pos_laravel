@extends('layouts.master')

@section('title')
Database Setting
@endsection

@section('breadcrumb')
@parent
<li class="active">Database Setting</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Pengaturan Basis Data</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <a href="{{ route('backup') }}" class="btn btn-block btn-warning"><i class="fa fa-database"></i> Buat Backup Basis Data</a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <a href="{{ route('migrate') }}" class="btn btn-block btn-primary"><i class="fa fa-database"></i> Jalankan Migration</a>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info">
                    <p>Perhatian: Pastikan Anda telah membuat backup basis data sebelum menjalankan migrasi.</p>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <h4>File Backup Tersedia:</h4>
                            @if(count($backups ?? []) > 0)
                            <ul>
                                @foreach($backups as $backup)
                                <li><a href="{{ asset('storage/app/' . env('APP_NAME') . '/' . $backup) }}" download>{{ $backup }}</a></li>
                                @endforeach
                            </ul>
                            @else
                            <p>Tidak ada file backup. Silakan buat backup baru.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
// Tambahkan skrip yang diperlukan di sini
@endpush