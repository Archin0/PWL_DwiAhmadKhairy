@extends('layouts.template')

@section('content')
  <div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-center align-items-center">
        <div class="card-tools d-flex justify-content-center flex-wrap">
          <button onclick="modalAction('{{ url('/user/import') }}')" 
                  class="btn btn-lg btn-info mr-5 mb-2">
              Import User (.xlsx)
          </button>
          <button onclick="modalAction('{{ url('/user/create_ajax') }}')" 
                  class="btn btn-lg btn-success mb-2">
              Tambah User
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
        <table class="table table-striped table-row-bordered" id="table_user">
            <thead>
                <tr><th>Foto Profil</th><th>Username</th><th>Nama</th><th>Level User</th><th>Aksi</th></tr>
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
        
    var dataUser;
    $(document).ready(function() {
      dataUser = $('#table_user').DataTable({
          // serverSide: true, jika ingin menggunakan server side processing
          serverSide: true,
          ajax: {
              "url": "{{ url('user/list') }}",
              "dataType": "json",
              "type": "POST",
                    "data" : function(d) {
                        d.id_level = $('#id_level').val();
                    }
          },
          columns: [
            {
              // nomor urut dari laravel datatable addIndexColumn()
              data: "foto_profil",
              className: "text-center",
              orderable: false,
              searchable: false
            },{
              data: "username",
              className: "",
              // orderable: true, jika ingin kolom ini bisa diurutkan
              orderable: true,
              // searchable: true, jika ingin kolom ini bisa dicari
              searchable: true
            },{
              data: "nama",
              className: "",
              orderable: true,
              searchable: true
            },{
              // mengambil data level hasil dari ORM berelasi
              data: "level.nama_level",
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

      $('#id_level').on('change', function() {
            dataUser.ajax.reload();
        });
      
      //tidak reload otomatis
      // $('#table_user_filter input').unbind().bind().on('keyup', function(e){
      //    if(e.keyCode == 13){ // enter key
      //        dataUser.search(this.value).draw();
      //    }
      // });

      //reload otomatis dengan delay 300ms
      var searchTimer;
      $('#table_user_filter input').off('keyup').on('keyup', function() {
          clearTimeout(searchTimer);
          searchTimer = setTimeout(function() {
              dataUser.search($('#table_user_filter input').val()).draw();
          }, 300); // Delay 300ms setelah pengetikan berhenti
      });
    });
  </script>
@endpush