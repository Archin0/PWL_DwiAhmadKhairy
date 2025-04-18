@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-center align-items-center">
        <div class="card-tools d-flex justify-content-center flex-wrap">
          <button onclick="modalAction('{{ url('/supplier/import') }}')" 
                  class="btn btn-lg btn-info mr-5 mb-2">
              Import Data Supplier (.xlsx)
          </button>
          <a href="{{ url('/supplier/export_excel') }}" class="btn btn-lg btn-primary mr-5 mb-2"><i class="fa fa-file-excel"></i> Export Data Barang (.xlsx)</a> 
          <button onclick="modalAction('{{ url('/supplier/create_ajax') }}')" 
                  class="btn btn-lg btn-success mb-2">
              Tambah Supplier
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
        {{-- Filtering
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
        </div> --}}
        <table class="table table-striped table-row-bordered" id="table_supplier">
            <thead>
                <tr><th>ID</th><th>Kode Supplier</th><th>Nama Supplier</th><th>Alamat</th><th>Aksi</th></tr>
            </thead>
        </table>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static"data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('css')
@endpush

@push('js')
  <script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }
        
    var dataSupplier;
    $(document).ready(function() {
      dataSupplier = $('#table_supplier').DataTable({
          // serverSide: true, jika ingin menggunakan server side processing
          serverSide: true,
          ajax: {
              "url": "{{ url('supplier/list') }}",
              "dataType": "json",
              "type": "POST",
              // "data" : function(d) {
              //     d.id_level = $('#id_level').val();
              // }
          },
          columns: [
            {
              // nomor urut dari laravel datatable addIndexColumn()
              data: "DT_RowIndex",
              className: "text-center",
              orderable: false,
              searchable: false
            },{
              data: "kode_supplier",
              className: "",
              // orderable: true, jika ingin kolom ini bisa diurutkan
              orderable: true,
              // searchable: true, jika ingin kolom ini bisa dicari
              searchable: true
            },{
              data: "nama_supplier",
              className: "",
              orderable: true,
              searchable: true
            },{
              data: "alamat",
              className: "",
              orderable: false,
              searchable: false
            },{
              data: "aksi",
              className: "",
              orderable: false,
              searchable: false
            }
          ]
      });

      //reload otomatis dengan delay 300ms
      var searchTimer;
      $('#table_supplier_filter input').off('keyup').on('keyup', function() {
          clearTimeout(searchTimer);
          searchTimer = setTimeout(function() {
              dataSupplier.search($('#table_supplier_filter input').val()).draw();
          }, 300); // Delay 300ms setelah pengetikan berhenti
      });
    });
  </script>
@endpush