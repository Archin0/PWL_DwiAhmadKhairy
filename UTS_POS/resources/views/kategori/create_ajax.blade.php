<form action="{{ url('kategori/ajax') }}" method="POST" id="form-tambah" enctype="multipart/form-data">
     @csrf
     <div id="modal-master" class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Tambah Data Kategori</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                         aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body">
                 <div class="form-group">
                     <label>Kode Kategori</label>
                     <input value="" type="text" name="kode_kategori" id="kode_kategori" class="form-control" required>
                     <small id="error-kode_kategori" class="error-text form-text text-danger"></small>
                 </div>
                 <div class="form-group">
                     <label>Nama Kategori</label>
                     <input value="" type="text" name="nama_kategori" id="nama_kategori" class="form-control" required>
                     <small id="error-nama_kategori" class="error-text form-text text-danger"></small>
                 </div>
                 <div class="form-group">
                     <label>Deskripsi</label>
                     <input value="" type="text" name="deskripsi" id="deskripsi" class="form-control" required>
                     <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                 </div>
                 <div class="form-group">
                     <label>Pilih Foto Kategori</label>
                     <input type="file" name="foto_kategori" id="foto_kategori" class="form-control" required>
                     <small class="form-text text-muted">Format: PNG. Maksimal 2MB. Background Transparan</small>
                     <small id="error-foto_kategori" class="error-text form-text text-danger"></small>
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
         $("#form-tambah").validate({
             rules: {
                kode_kategori: {
                    required: true,
                    minlength: 3,
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
                    required: true,
                    extension: "png",
                    filesize: 2048 // 2MB in KB
                }
             },
             messages: {
                foto_kategori: {
                    extension: "Hanya file gambar (PNG) dengan background transparan yang diperbolehkan"
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
                            loadKategoriCards();
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
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal mengunggah file'
                        });
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

         // Add custom validator for file size
         $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param * 1024);
         }, 'Ukuran file maksimal 2MB');
     });
 </script>