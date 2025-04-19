@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-center align-items-center">
        <div class="card-tools d-flex justify-content-center flex-wrap">
            <button onclick="modalAction('{{ url('/supply/import') }}')" class="btn btn-lg btn-info mr-5">Import Data Supply (.xlsx)</button> 
                <a href="{{ url('/supply/export_excel') }}" class="btn btn-lg btn-primary mr-5"><i class="fa fa-file-excel"></i> Export Data Supply (.xlsx)</a> 
            <button onclick="modalAction('{{ url('/supply/create_ajax') }}')" 
                    class="btn btn-primary btn-lg">
                <i class="fa fa-plus"></i> Tambah Supply
            </button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        
        <table class="table table-striped table-row-bordered" id="table_supply">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Beli</th>
                    <th>Operator</th>
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
@endpush

@push('js')
<script>
    var dataSupply;
    $(document).ready(function() {
        dataSupply = $('#table_supply').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: "{{ url('supply/list') }}",
                type: "POST",
            },
            columns: [
                { 
                    data: "created_at", 
                    className: "text-center",
                    orderable: true,
                    searchable: true,
                    // render: function(data) {
                    //     return new Date(data).toLocaleDateString('id-ID', {
                    //         day: '2-digit',
                    //         month: '2-digit',
                    //         year: 'numeric',
                    //         hour: '2-digit',
                    //         minute: '2-digit'
                    //     });
                    // }
                },
                { 
                    data: "barang.kode_barang", 
                    className: "text-center",
                    orderable: true,
                    searchable: true
                },
                { 
                    data: "barang.nama_barang", 
                    className: "",
                    orderable: true,
                    searchable: true
                },
                { 
                    data: "jumlah", 
                    className: "text-center",
                    orderable: true,
                    searchable: false
                },
                { 
                    data: "harga_beli", 
                    className: "text-center",
                    orderable: true,
                    searchable: false,
                    render: function(data) {
                        return 'Rp' + parseInt(data).toLocaleString('id-ID');
                    }
                },
                { 
                    data: "user.nama", 
                    className: "",
                    orderable: true,
                    searchable: true
                },
                { 
                    data: "aksi", 
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ],
            order: [[0, 'desc']],
        });
    });

    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
</script>
@endpush