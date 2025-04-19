<form action="{{ url('supply/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Stok Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if(request('id_barang'))
                    <!-- Mode langsung dari barang -->
                    <input type="hidden" name="id_barang" value="{{ request('id_barang') }}">
                    
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Barang</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" value="{{ $barang->kode_barang }} - {{ $barang->nama_barang }}" readonly>
                        </div>
                    </div>
                @else
                    <!-- Mode pilih barang -->
                    {{-- <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Cari Barang</label>
                        <div class="col-sm-9">
                            <select name="id_barang" id="select-barang" class="form-control select2" required>
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangList as $item)
                                    <option value="{{ $item->id_barang }}" 
                                        data-kode="{{ $item->kode_barang }}"
                                        data-nama="{{ $item->nama_barang }}">
                                        {{ $item->kode_barang }} - {{ $item->nama_barang }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Ketikan kode atau nama barang</small>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Cari Barang</label>
                        <div class="col-sm-9">
                            <select name="id_barang" id="select-barang" class="form-control select2" required style="width: 100%;">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barangList->sortBy('kode_barang') as $item)
                                    <option value="{{ $item->id_barang }}" 
                                        data-kode="{{ $item->kode_barang }}"
                                        data-nama="{{ $item->nama_barang }}"
                                        data-stok="{{ $item->stok }}">
                                        {{ $item->kode_barang }} - {{ $item->nama_barang }} - Stok: {{ $item->stok }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Ketik kode atau nama barang</small>
                        </div>
                    </div>
                @endif
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Jumlah</label>
                    <div class="col-sm-9">
                        <input type="number" name="jumlah" class="form-control" required min="1">
                        <small class="form-text text-muted">Jumlah stok yang ditambahkan</small>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Harga Beli</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" name="harga_beli" id="harga-beli" class="form-control" required>
                        </div>
                        <small class="form-text text-muted">Harga beli per unit</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(2.25rem + 2px);
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for barang selection
    $('#select-barang').select2({
        placeholder: "Ketik kode atau nama barang...",
        allowClear: true,
        width: '100%',
        matcher: function(params, data) {
            // Jika pencarian kosong, tampilkan semua
            if ($.trim(params.term) === '') {
                return data;
            }
            
            // Normalisasi teks (hilangkan case sensitivity)
            var term = params.term.toLowerCase();
            var kodeBarang = data.element.getAttribute('data-kode').toLowerCase();
            var namaBarang = data.element.getAttribute('data-nama').toLowerCase();
            var stokBarang = data.element.getAttribute('data-stok').toLowerCase();
            
            // Cek apakah cocok dengan kode atau nama barang
            if (kodeBarang.indexOf(term) > -1 || namaBarang.indexOf(term) > -1 || stokBarang.indexOf(term) > -1) {
                return data;
            }
            
            // Jika tidak cocok
            return null;
        },
        templateResult: function(data) {
            // Tampilkan teks highlight jika sedang mencari
            if (!data.id) {
                return data.text;
            }
            
            var term = $('#select-barang').data('select2').dropdown.$search.val().toLowerCase();
            var kode = data.element.getAttribute('data-kode');
            var nama = data.element.getAttribute('data-nama');
            var stok = data.element.getAttribute('data-stok');
            
            // Highlight teks yang cocok
            if (term) {
                kode = kode.replace(new RegExp('(' + term + ')', 'gi'), '<strong>$1</strong>');
                nama = nama.replace(new RegExp('(' + term + ')', 'gi'), '<strong>$1</strong>');
                stok = stok.replace(new RegExp('(' + term + ')', 'gi'), '<strong>$1</strong>');
            }
            
            var $result = $(
                '<div>' + kode + ' - ' + nama + ' - ' + stok '</div>'
            );
            
            return $result;
        }
    });

    // Format Rupiah for harga beli
    $('#harga-beli').on('keyup', function() {
        let value = $(this).val().replace(/\D/g, '');
        $(this).val(formatRupiah(value));
    });

    function formatRupiah(angka) {
        if (!angka) return '';
        let number_string = angka.toString(),
            sisa = number_string.length % 3,
            rupiah = number_string.substr(0, sisa),
            ribuan = number_string.substr(sisa).match(/\d{3}/g);
            
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    $("#form-tambah").validate({
        rules: {
            id_barang: {
                required: true
            },
            jumlah: {
                required: true,
                min: 1
            },
            harga_beli: {
                required: true,
                minlength: 3
            }
        },
        submitHandler: function(form) {
            // Convert harga_beli back to number before submit
            let harga = $('#harga-beli').val().replace(/\./g, '');
            $('#harga-beli').val(harga);
            
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
                        // Reload data
                        if (typeof loadBarangCards !== 'undefined') {
                            loadBarangCards();
                        }
                        if (typeof dataSupply !== 'undefined') {
                            dataSupply.ajax.reload();
                        }
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
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan'
                    });
                }
            });
            return false;
        }
    });
});
</script>
@endpush