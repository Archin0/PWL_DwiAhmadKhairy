@empty($kategori)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" arialabel="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/kategori') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kategori/' . $kategori->id_kategori . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" arialabel="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Kategori</label>
                        <input value="{{ $kategori->kode_kategori }}" type="text" name="kode_kategori" id="kode_kategori" class="form-control">
                        <small id="error-kode_kategori" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Kategori</label>
                        <input value="{{ $kategori->nama_kategori }}" type="text" name="nama_kategori" id="nama_kategori" class="form-control">
                        <small id="error-nama_kategori" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                     <label>Deskripsi</label>
                     <input value="{{ $kategori->deskripsi }}" type="text" name="deskripsi" id="deskripsi" class="form-control">
                     <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                 </div>
                 <div class="form-group">
                    <label>Foto Kategori Saat Ini: </label>
                    @if($kategori->foto_kategori)
                        <img src="{{ asset('storage/kategori/'.$kategori->foto_kategori) }}" class="img-thumbnail mb-2" style="max-height: 100px;">
                    @else
                        <p>No photo available</p>
                    @endif
                </div>
                 <div class="form-group">
                     <label>Edit Foto Kategori</label>
                     <input type="file" name="foto_kategori" id="foto_kategori" class="form-control">
                     <small class="form-text text-muted">Format: PNG. Maksimal 2MB. Background Transparan</small>
                     <small id="error-foto_kategori" class="error-text form-text text-danger"></small>
                 </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btnwarning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(function () {
            $("#form-edit").validate({
                rules: {
                    kode_kategori: {
                        required: true,
                        minlength: 2,
                        maxlength: 10
                    },
                    nama_kategori: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    deskripsi: {
                        required: false,
                        minlength: 10,
                        maxlength: 255
                    },
                    foto_kategori: {
                        required: false,
                        extension: "png",
                        filesize: 2048 // 2MB in KB
                    }
                },
                submitHandler: function (form) {
                    var formData = new FormData(form);
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.status) {
                                $('#myModal').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });
                                loadKategoriCards();
                            } else {
                                $('.error-text').text('');
                                $.each(response.msgField, function (prefix, val) {
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
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            // Add custom validator for file size
            $.validator.addMethod('filesize', function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param * 1024);
            }, 'Ukuran file maksimal 2MB');
        });
    </script>
@endempty