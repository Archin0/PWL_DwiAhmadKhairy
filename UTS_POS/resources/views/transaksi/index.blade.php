@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Transaksi Penjualan</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <div class="form-group">
                    <label for="kode_transaksi">Kode Transaksi</label>
                    <input type="text" class="form-control" id="kode_transaksi" 
                           value="T{{ now()->setTimezone('Asia/Jakarta')->format('dmYHis') }}" readonly>
                </div>
                <div class="form-group">
                    <label for="pembeli">Nama Pembeli</label>
                    <input type="text" class="form-control" id="pembeli" placeholder="Masukkan nama pembeli">
                </div>
                
                <div class="table-responsive mt-4">
                    <table class="table table-bordered" id="table_items">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Items will be added here dynamically -->
                        </tbody>
                    </table>
                    <button id="add_item" class="btn btn-sm btn-primary mt-2">
                        <i class="fa fa-plus"></i> Tambah Barang
                    </button>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="summary-card p-3">
                    <h5 class="text-center mb-3">Rincian Transaksi</h5>
                    
                    <div class="form-group">
                        <label>Tanggal</label>
                        <p class="form-control-static">{{ now()->setTimezone('Asia/Jakarta')->format('d M. Y H:i') }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label>Kasir</label>
                        <p class="form-control-static">{{ Auth::user()->nama }}</p>
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <label>Subtotal</label>
                        <p class="form-control-static" id="subtotal">Rp 0</p>
                    </div>
                    
                    <div class="form-group">
                        <label>Diskon</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="diskon" placeholder="Masukkan diskon" value="0" min="0" max="100">
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="form-group">
                        <label>Total</label>
                        <h4 id="total_harga">Rp 0</h4>
                    </div>
                    
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select class="form-control" id="metode_pembayaran">
                            <option value="Card Only">Card Only</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                    
                    <button id="proses_transaksi" class="btn btn-success btn-block">
                        <i class="fa fa-check"></i> Proses Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for adding items -->
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">Pilih Barang</h5>
                <div class="input-group">
                    <input type="text" class="form-control" id="search_barang" placeholder="Cari berdasarkan kode atau nama barang...">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="barang_table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $item)
                                @if($item->stok > 0)
                                <tr class="barang-item" 
                                    data-id="{{ $item->id_barang }}"
                                    data-kode="{{ $item->kode_barang }}"
                                    data-nama="{{ $item->nama_barang }}"
                                    data-harga="{{ $item->harga }}"
                                    data-stok="{{ $item->stok }}">
                                    <td>{{ $item->kode_barang }}</td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>{{ $item->stok }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for item quantity -->
<div class="modal fade" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="quantityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quantityModalLabel">Tambah Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label id="barang_name">Nama Barang</label>
                    <input type="hidden" id="selected_barang_id">
                    <input type="hidden" id="selected_barang_kode">
                    <input type="hidden" id="selected_barang_nama">
                    <input type="hidden" id="selected_barang_harga">
                    <input type="hidden" id="selected_barang_stok">
                </div>
                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-secondary" type="button" id="decrement_qty">-</button>
                        </div>
                        <input type="number" class="form-control text-center" id="jumlah" min="1" value="1">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="increment_qty">+</button>
                        </div>
                    </div>
                    <small class="text-muted">Stok tersedia: <span id="available_stock"></span></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirm_add_item">Tambah</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .summary-card {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        background-color: #f8f9fa;
    }
    .form-control-static {
        font-weight: bold;
    }
    .barang-item:hover {
        cursor: pointer;
        background-color: #f8f9fa;
    }
    .barang-item.selected {
        background-color: #e9ecef;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Show modal when add item button clicked
        $('#add_item').click(function() {
            $('#itemModal').modal('show');
        });
        
        // Search functionality
        $('#search_barang').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#barang_table tbody tr').filter(function() {
                $(this).toggle(
                    $(this).text().toLowerCase().indexOf(value) > -1
                );
            });
        });
        
        // Select item from modal
        $(document).on('click', '.barang-item', function() {
            $('.barang-item').removeClass('selected');
            $(this).addClass('selected');
            
            const id = $(this).data('id');
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');
            const stok = $(this).data('stok');
            
            $('#selected_barang_id').val(id);
            $('#selected_barang_kode').val(kode);
            $('#selected_barang_nama').val(nama);
            $('#selected_barang_harga').val(harga);
            $('#selected_barang_stok').val(stok);
            
            $('#barang_name').text(nama);
            $('#available_stock').text(stok);
            $('#jumlah').val(1).attr('max', stok);
            
            $('#itemModal').modal('hide');
            $('#quantityModal').modal('show');
        });
        
        // Quantity adjustment
        $('#increment_qty').click(function() {
            const current = parseInt($('#jumlah').val());
            const max = parseInt($('#selected_barang_stok').val());
            if (current < max) {
                $('#jumlah').val(current + 1);
            }
        });
        
        $('#decrement_qty').click(function() {
            const current = parseInt($('#jumlah').val());
            if (current > 1) {
                $('#jumlah').val(current - 1);
            }
        });
        
        // Add item to table
        $('#confirm_add_item').click(function() {
            const id = $('#selected_barang_id').val();
            const kode = $('#selected_barang_kode').val();
            const nama = $('#selected_barang_nama').val();
            const harga = $('#selected_barang_harga').val();
            const jumlah = $('#jumlah').val();
            const stok = $('#selected_barang_stok').val();
            
            if (!id || !jumlah) {
                alert('Silakan pilih barang dan masukkan jumlah');
                return;
            }
            
            if (parseInt(jumlah) > parseInt(stok)) {
                alert('Jumlah melebihi stok yang tersedia');
                return;
            }
            
            const subtotal = harga * jumlah;
            
            // Check if item already exists in table
            let exists = false;
            $('#table_items tbody tr').each(function() {
                if ($(this).data('id') == id) {
                    exists = true;
                    const currentJumlah = parseInt($(this).find('.item-jumlah').text());
                    const newJumlah = currentJumlah + parseInt(jumlah);
                    $(this).find('.item-jumlah').text(newJumlah);
                    
                    const currentSubtotal = parseInt($(this).find('.item-subtotal').text().replace('Rp ', '').replace(/\./g, ''));
                    const newSubtotal = currentSubtotal + subtotal;
                    $(this).find('.item-subtotal').text('Rp ' + newSubtotal.toLocaleString('id-ID'));
                    
                    return false;
                }
            });
            
            if (!exists) {
                const row = `
                    <tr data-id="${id}" data-harga="${harga}">
                        <td>${kode}</td>
                        <td>${nama}</td>
                        <td class="item-harga">Rp ${parseInt(harga).toLocaleString('id-ID')}</td>
                        <td class="item-jumlah">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary btn-sm adjust-qty" data-action="decrement">-</button>
                                </div>
                                <input type="text" class="form-control form-control-sm text-center item-qty" value="${jumlah}" readonly>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-sm adjust-qty" data-action="increment">+</button>
                                </div>
                            </div>
                        </td>
                        <td class="item-subtotal">Rp ${subtotal.toLocaleString('id-ID')}</td>
                        <td>
                            <button class="btn btn-sm btn-danger remove-item">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#table_items tbody').append(row);
            }
            
            // Update totals
            updateTotals();
            
            // Reset modals
            $('#quantityModal').modal('hide');
            $('.barang-item').removeClass('selected');
        });
        
        // Adjust quantity in table
        $(document).on('click', '.adjust-qty', function() {
            const action = $(this).data('action');
            const row = $(this).closest('tr');
            const qtyInput = row.find('.item-qty');
            let qty = parseInt(qtyInput.val());
            const harga = parseInt(row.data('harga'));
            const stok = parseInt(row.find('.remove-item').data('stok') || 9999); // Fallback if stok not available
            
            if (action === 'increment') {
                if (qty < stok) {
                    qty++;
                } else {
                    alert('Jumlah melebihi stok yang tersedia');
                    return;
                }
            } else if (action === 'decrement') {
                if (qty > 1) {
                    qty--;
                } else {
                    return;
                }
            }
            
            qtyInput.val(qty);
            const subtotal = harga * qty;
            row.find('.item-subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
            
            updateTotals();
        });
        
        // Remove item from table
        $(document).on('click', '.remove-item', function() {
            $(this).closest('tr').remove();
            updateTotals();
        });
        
        // Calculate diskon and total
        $('#diskon').on('input', function() {
            updateTotals();
        });
        
        // Process transaction
        $('#proses_transaksi').click(function() {
            const pembeli = $('#pembeli').val();
            const metode_pembayaran = $('#metode_pembayaran').val();
            
            if ($('#table_items tbody tr').length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Silakan tambahkan barang terlebih dahulu',
                });
                return;
            }
            
            if (!pembeli) {
                Swal.fire({
                        icon: 'error',
                        title: 'Silakan masukkan nama pembeli',
                    });
                return;
            }
            
            // Prepare data for AJAX
            const items = [];
            $('#table_items tbody tr').each(function() {
                items.push({
                    id_barang: $(this).data('id'),
                    jumlah: parseInt($(this).find('.item-qty').val()),
                    subtotal: parseInt($(this).find('.item-subtotal').text().replace('Rp ', '').replace(/\./g, ''))
                });
            });
            
            const subtotal = items.reduce((sum, item) => sum + item.subtotal, 0);
            const diskon = parseInt($('#diskon').val()) || 0;
            const total_harga = diskon > 0 ? subtotal - (subtotal * diskon / 100) : subtotal;
            
            const data = {
                kode_transaksi: $('#kode_transaksi').val(),
                pembeli: pembeli,
                total_barang: items.length,
                diskon: diskon,
                total_harga: total_harga,
                metode_pembayaran: metode_pembayaran,
                items: items
            };
            
            // Send data to server
            $.ajax({
                url: "{{ route('transaksi.store') }}",
                type: "POST",
                data: data,
                success: function(response) {
                    if (response.success) {
                        // Open PDF in new tab
                        window.open("{{ url('transaksi/export_pdf') }}/" + response.id_transaksi, '_blank');
                        
                        // Reset form dengan me-reload halaman
                        window.location.reload();
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                    });
                }
            });
        });
        
        function updateTotals() {
            // Calculate subtotal
            let subtotal = 0;
            $('#table_items tbody tr').each(function() {
                subtotal += parseInt($(this).find('.item-subtotal').text().replace('Rp ', '').replace(/\./g, ''));
            });
            
            $('#subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
            
            // Calculate total with discount
            const diskon = parseInt($('#diskon').val()) || 0;
            const total = diskon > 0 ? subtotal - (subtotal * diskon / 100) : subtotal;
            $('#total_harga').text('Rp ' + total.toLocaleString('id-ID'));
        }
    });
</script>
@endpush