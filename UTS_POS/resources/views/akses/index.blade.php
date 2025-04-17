@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        {{-- Filtering --}}
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="id_level" name="id_level" required>
                            <option value="">- Semua -</option>
                            @foreach($level as $item)
                                <option value="{{ $item->id_level }}">{{ $item->nama_level }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Level User</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-striped table-row-bordered" id="table_akses">
            <thead>
                <tr><th>Foto Profil</th><th>Username</th><th>Nama</th><th>Kelola Akun</th><th>Kelola Barang</th><th>Transaksi</th><th>Laporan</th></tr>
            </thead>
        </table>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<style>
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
    }
</style>
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }
    
    var dataAkses;
    $(document).ready(function() {
        dataAkses = $('#table_akses').DataTable({
            serverSide: true,
            // processing: true,
            ajax: {
                "url": "{{ url('akses/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.id_level = $('#id_level').val();
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                {
                    data: "foto_profil",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },{
                    data: "user.username",
                    className: "",
                    orderable: true,
                    searchable: true
                },{
                    data: "user.nama",
                    className: "",
                    orderable: true,
                    searchable: true
                },{
                    data: "kelola_akun",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },{
                    data: "kelola_barang",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },{
                    data: "transaksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },{
                    data: "laporan",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#id_level').on('change', function() {
            dataAkses.ajax.reload();
        });

        // Handle checkbox changes
        $(document).on('change', '.access-checkbox', function() {
            const id = $(this).data('id');
            const field = $(this).data('field');
            const value = $(this).is(':checked') ? 1 : 0;
            
            $.ajax({
                url: "{{ url('akses/update_akses') }}",
                type: "POST",
                data: {
                    id: id,
                    field: field,
                    value: value,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    toastr.error('Terjadi kesalahan');
                    // Revert checkbox on error
                    $(this).prop('checked', !value);
                }
            });
        });

        //reload otomatis dengan delay 300ms
        var searchTimer;
        $('#table_akses_filter input').off('keyup').on('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                dataAkses.search($('#table_akses_filter input').val()).draw();
            }, 300); // Delay 300ms setelah pengetikan berhenti
        });
    });
</script>
@endpush