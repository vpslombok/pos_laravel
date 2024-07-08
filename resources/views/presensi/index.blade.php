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
                <form id="filterForm" class="form-inline">
                    <div class="form-group">
                        <label for="filterDari">Dari Tanggal:</label>
                        <input type="date" class="form-control" id="filterDari" name="dari_tanggal">
                    </div>
                    <div class="form-group">
                        <label for="filterSampai">Sampai Tanggal:</label>
                        <input type="date" class="form-control" id="filterSampai" name="sampai_tanggal">
                    </div>
                    <button type="button" class="btn btn-primary ml-2" id="filterButton">Filter</button>
                    <button type="button" class="btn btn-success ml-2" id="exportExcelButton">Export to Excel</button>
                    <button type="button" class="btn btn-danger ml-2" id="exportPdfButton">Export to PDF</button>
                </form>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th width="10%">Nik</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Total Jam</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@includeIf('presensi.edit')
@endsection

@push('scripts')
<script>
    $(function() {
        let table;

        function loadDataTable(filterParams = {}) {
            table = $('.table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                autoWidth: false,
                ajax: {
                    url: `{{ route('presensi.data') }}`,
                    type: 'GET',
                    data: filterParams,
                    dataSrc: function(json) {
                        return json.data;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        searchable: false,
                        sortable: false
                    },
                    {
                        data: 'nik'
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'tanggal'
                    },
                    {
                        data: 'waktu_masuk'
                    },
                    {
                        data: 'waktu_keluar'
                    },
                    {
                        data: 'total_jam'
                    },
                    {
                        data: 'aksi',
                        searchable: false,
                        sortable: false
                    }
                ]
            });
        }

        loadDataTable(); // Load DataTable when the page first loads

        function reloadTable() {
            table.ajax.reload();
        }

        // Submit the edit form
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                url: form.attr('action'),
                method: 'PUT',
                data: form.serialize(),
                success: function(response) {
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message,
                    });
                },
                error: function(errors) {
                    if (errors.responseJSON && errors.responseJSON.message) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errors.responseJSON.message,
                        });
                    }
                }
            });
        });

        // Delete data function
        window.deleteData = function(url) {
            Swal.fire({
                title: 'Yakin?',
                text: "Tidak dapat mengembalikan data yang telah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                        '_token': $('[name=csrf-token]').attr('content'),
                        '_method': 'post'
                    }).done(function(response) {
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                        });
                    }).fail(function(errors) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Tidak dapat menghapus data',
                        });
                        if (errors.responseJSON && errors.responseJSON.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: errors.responseJSON.message,
                            });
                        }
                    });
                }
            });
        };

        // Function to handle modal editing
        window.editModal = function(url) {
            $.get(url, function(data) {
                $('#editForm').attr('action', url);
                $('#nik').val(data.user.nik);
                $('#nama').val(data.user.name);
                $('#tanggal').val(data.tanggal);
                $('#waktu_masuk').val(data.waktu_masuk);
                $('#waktu_keluar').val(data.waktu_keluar);
                $('#editModal').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Tidak dapat mengambil data',
                });
            });
        };

        // Handle click event for edit button
        $(document).on('click', '.edit-button', function() {
            const url = $(this).data('url');
            editModal(url);
        });

        // Handle filter button click event
        $('#filterButton').on('click', function() {
            let filterDari = $('#filterDari').val();
            let filterSampai = $('#filterSampai').val();

            let filterParams = {};
            if (filterDari) {
                filterParams.dari_tanggal = filterDari;
            }
            if (filterSampai) {
                filterParams.sampai_tanggal = filterSampai;
            }

            // Destroy previous DataTable instance and load new DataTable with filters
            if ($.fn.DataTable.isDataTable('.table')) {
                table.destroy();
            }
            loadDataTable(filterParams);
        }).fail(function(errors) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Tidak dapat memfilter data',
            });
            if (errors.responseJSON && errors.responseJSON.message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: errors.responseJSON.message,
                });
            }
        });

        // Export to Excel
        $('#exportExcelButton').on('click', function() {
            let filterDari = $('#filterDari').val();
            let filterSampai = $('#filterSampai').val();

            let filterParams = {};
            if (filterDari) {
                filterParams.dari_tanggal = filterDari;
            }
            if (filterSampai) {
                filterParams.sampai_tanggal = filterSampai;
            }

            window.location.href = `{{ route('presensi.export_excel') }}?${$.param(filterParams)}`;
        });

        // Export to PDF
        $('#exportPdfButton').on('click', function() {
            let filterDari = $('#filterDari').val();
            let filterSampai = $('#filterSampai').val();

            let filterParams = {};
            if (filterDari) {
                filterParams.dari_tanggal = filterDari;
            }
            if (filterSampai) {
                filterParams.sampai_tanggal = filterSampai;
            }

            window.location.href = `{{ route('presensi.export_pdf') }}?${$.param(filterParams)}`;
        });
    });
</script>
@endpush