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
        <div class="box">
            <div class="box-body">
                <div class="form-group row">
                    <div class="col-lg-12 text-right">
                        <a href="{{ route('migrate') }}" class="btn btn-sm btn-flat btn-primary"><i class="fa fa-database"></i> Jalankan Migration</a>
                    </div>
                </div>
                <div class="alert alert-info">
                    <p>Perhatian: Pastikan Anda telah membuat backup basis data sebelum menjalankan migrasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')