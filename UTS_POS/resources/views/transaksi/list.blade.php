@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-center align-items-center">
        <div class="card-tools d-flex justify-content-center flex-wrap">
            <a href="{{ url('transaksi') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus"></i> Buat Transaksi Baru
            </a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <table class="table table-striped table-row-bordered" id="table_transaksi">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Transaksi</th>
                    <th>Kasir</th>
                    <th>Pembeli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" 
     data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
<style>
    /* Optional: Custom styling for the table */
    #table_transaksi {
        width: 100% !important;
    }
    .btn-action {
        margin: 0 2px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }
</style>
@endpush

@push('js')
<script>
    var dataTransaksi;
    $(document).ready(function() {
        dataTransaksi = $('#table_transaksi').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('transaksi/list') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}"
                }
            },
            columns: [
                { 
                data: "created_at", 
                className: "text-center",
                render: function(data) {
                    return new Date(data).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
                },
                { 
                    data: "kode_transaksi", 
                    className: "text-center"
                },
                { 
                    data: "user.nama", 
                    className: "text-center"
                },
                { 
                    data: "pembeli", 
                    className: "text-center"
                },
                { 
                    data: "aksi", 
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[0, 'desc']] // Default order by tanggal descending
        });
    });

    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
</script>
@endpush