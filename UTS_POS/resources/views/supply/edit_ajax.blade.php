<form action="{{ url('/supply/'.$supply->id_supply.'/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Supply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Barang</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="{{ $supply->barang->nama_barang }}" readonly>
                        <input type="hidden" name="id_barang" value="{{ $supply->id_barang }}">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Jumlah</label>
                    <div class="col-sm-9">
                        <input type="number" name="jumlah" class="form-control" required min="1" value="{{ $supply->jumlah }}">
                        <small class="form-text text-muted">Jumlah stok yang ditambahkan</small>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Harga Beli</label>
                    <div class="col-sm-9">
                        <input type="number" name="harga_beli" class="form-control" required min="1" value="{{ $supply->harga_beli }}">
                        <small class="form-text text-muted">Harga beli per unit</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-edit").validate({
        rules: {
            jumlah: {
                required: true,
                min: 1
            },
            harga_beli: {
                required: true,
                min: 1
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataSupply.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON.message || 'Terjadi kesalahan'
                    });
                }
            });
            return false;
        }
    });
});
</script>