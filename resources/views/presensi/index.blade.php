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
                <table class="table table-striped table-bordered" id="tablePresensi">
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
                            <td>{{ $presensi['waktu_keluar'] ? $presensi['waktu_keluar'] : 'Belum Pulang' }}</td>
                            <td>{{ $presensi['total_jam'] ? $presensi['total_jam'] : 'Belum Pulang' }}</td>
                            <td>
                                <a href="{{ route('presensi.update', $presensi['id']) }}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="#" class="btn btn-danger btn-sm" onclick="hapus(`{{ $presensi['id'] }}`)">Hapus</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@includeIf('presensi.edit')
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            searching: true
        });

        // Show modal and fill data
        $('.btn-warning').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');

            // Get data from the row
            const row = $(this).closest('tr');
            const id = row.find('td').eq(0).text();
            const nik = row.find('td').eq(1).text();
            const nama = row.find('td').eq(2).text();
            const tanggal = row.find('td').eq(3).text();
            const waktuMasuk = row.find('td').eq(4).text();
            const waktuKeluar = row.find('td').eq(5).text();

            // Set modal input values
            $('#nik').val(nik);
            $('#nama').val(nama);
            $('#tanggal').val(tanggal);
            $('#waktu_masuk').val(waktuMasuk);
            $('#waktu_keluar').val(waktuKeluar);
            $('#editForm').attr('action', url);

            $('#editModal').modal('show');
        });

        // Submit form for update
        $('#editForm').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            const url = $(this).attr('action');

            $.ajax({
                type: 'POST',
                url: url,
                data: formData,
                success: function(response) {
                    if (response.success === 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(function() {
                            location.reload();
                        });
                    } else if (response.success === 400) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mengupdate data: ' + response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Terjadi kesalahan: ' + error,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    });

    function hapus(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'GET',
                    url: `{{ route('presensi.delete', '') }}/${id}`,
                    success: function(response) {
                        if (response.success === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan: ' + error,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }

   
</script>
@endpush