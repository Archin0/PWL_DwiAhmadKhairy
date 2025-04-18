@empty($barang)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/barang') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/barang/' . $barang->id_barang . '/update_ajax') }}" method="POST" id="form-edit" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="id_kategori" id="id_kategori" class="form-control" required>
                            <option value="">- Pilih Kategori -</option>
                            @foreach ($kategori as $k)
                                <option {{ $k->id_kategori == $barang->id_kategori ? 'selected' : '' }}
                                    value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_kategori" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Supplier</label>
                        <select name="id_supplier" id="id_supplier" class="form-control" required>
                            <option value="">- Pilih Supplier -</option>
                            @foreach ($supplier as $s)
                                <option {{ $s->id_supplier == $barang->id_supplier ? 'selected' : '' }}
                                    value="{{ $s->id_supplier }}">{{ $s->nama_supplier }}</option>
                            @endforeach
                        </select>
                        <small id="error-id_supplier" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Kode Barang</label>
                        <input value="{{ $barang->kode_barang }}" type="text" name="kode_barang" id="kode_barang" class="form-control" required>
                        <small id="error-kode_barang" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input value="{{ $barang->nama_barang }}" type="text" name="nama_barang" id="nama_barang" class="form-control" required>
                        <small id="error-nama_barang" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input value="{{ $barang->harga }}" type="number" name="harga" id="harga" class="form-control" required>
                        <small id="error-harga" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Stok</label>
                        <input value="{{ $barang->stok }}" type="number" name="stok" id="stok" class="form-control" required>
                        <small id="error-stok" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Foto Barang Saat Ini</label>
                        @if($barang->foto_barang)
                            <img src="{{ asset('storage/barang/'.$barang->foto_barang) }}" class="img-thumbnail mb-2" style="max-height: 100px;">
                            <input type="hidden" name="foto_lama" value="{{ $barang->foto_barang }}">
                        @else
                            <p>Tidak ada foto</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Edit Foto Barang</label>
                        <input type="file" name="foto_barang" id="foto_barang" class="form-control">
                        <small class="form-text text-muted">Format: JPG, PNG, JPEG. Maksimal 2MB</small>
                        <small id="error-foto_barang" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function() {
            $("#form-edit").validate({
                rules: {
                    id_kategori: {
                        required: true,
                        number: true
                    },
                    id_supplier: {
                        required: true,
                        number: true
                    },
                    kode_barang: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    nama_barang: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    harga: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    stok: {
                        required: true,
                        digits: true,
                        min: 0
                    },
                    foto_barang: {
                        // accept: ".jpeg|.jpg|.png|.avif|.webp",
                        accept: "image/*",
                        filesize: 2000 // 2MB in KB
                    }
                },
                messages: {
                    foto_barang: {
                        accept: "Hanya file gambar yang diperbolehkan",
                        filesize: "Ukuran file maksimal 2MB"
                    }
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                loadBarangCards();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            var response = xhr.responseJSON;
                            if (response) {
                                $('.error-text').text('');
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                });
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Terjadi Kesalahan',
                                    text: response.message
                                });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
            
            // Custom validator for file size
            $.validator.addMethod('filesize', function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param * 1024);
            }, 'Ukuran file terlalu besar');
        });
    </script>
@endempty