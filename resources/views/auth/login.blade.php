@extends('layouts.auth')

@section('login')
<div class="login-box">

    <!-- /.login-logo -->
    <div class="login-box-body">
        <div class="login-logo" style="border-radius: 50px;">
            <a href="{{ url('/') }}">
                <img src="{{ url($setting->path_logo) }}" alt="logo.png" width="150" style="border-radius: 50px;">
            </a>
        </div>
        <form action="{{ route('login') }}" method="post" class="form-login">
            @csrf
            <div class="form-group has-feedback @error('nik') has-error @enderror">
                <input type="text" name="nik" class="form-control" placeholder="NIK" required value="{{ old('nik') }}" autofocus>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @error('nik')
                    <span class="help-block">{{ $message }}</span>
                @else
                <span class="help-block with-errors"></span>
                @enderror
            </div>
            <div class="form-group has-feedback @error('password') has-error @enderror">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @error('password')
                    <span class="help-block">{{ $message }}</span>
                @else
                    <span class="help-block with-errors"></span>
                @enderror
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" value="remember"> Ingat Saya
                        </label>
                    </div>
                </div>
                <!-- /.col -->
               
                <div class="col-xs-7">
                    <a href="#" class="btn btn-success btn-block btn-flat" data-toggle="modal" data-target="#qrCodeModal">Presensi QR Code</a>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-info btn-block btn-flat">Masuk</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- Modal -->
@include('auth.qr-code')
@endsection