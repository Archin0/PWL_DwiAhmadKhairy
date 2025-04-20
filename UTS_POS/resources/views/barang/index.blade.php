@extends('layouts.template') 
 
@section('content') 
    <div class="card"> 
        <div class="card-header  d-flex justify-content-center align-items-center"> 
            <div class="card-tools d-flex justify-content-center flex-wrap"> 
                <button onclick="modalAction('{{ url('/barang/import') }}')" class="btn btn-info mr-5">Import Data Barang (.xlsx)</button> 
                <a href="{{ url('/barang/export_excel') }}" class="btn btn-primary mr-5"><i class="fa fa-file-excel"></i> Export Data Barang (.xlsx)</a> 
                {{-- <a href="{{ url('/barang/export_pdf') }}" class="btn btn-warning mr-5"><i class="fa fa-file-pdf"></i> Export Barang (.pdf)</a>  --}}
                <button onclick="modalAction('{{ url('/barang/create_ajax') }}')" class="btn btn-success">Tambah Barang</button> 
            </div> 
        </div> 
        <div class="card-body"> 
            <!-- Filter data --> 
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row"> 
                    <div class="col-md-12"> 
                        <div class="form-group form-group-sm row text-sm mb-0"> 
                            <label for="filter_date" class="col-md-1 col-form-label">Filter</label> 
                            <div class="col-md-3"> 
                                <select name="filter_kategori" class="form-control form-control-sm filter_kategori"> 
                                    <option value="">- Semua -</option> 
                                    @foreach($kategori as $l) 
                                        <option value="{{ $l->id_kategori }}">{{ $l->nama_kategori }}</option> 
                                    @endforeach 
                                </select> 
                                <small class="form-text text-muted">Kategori Barang</small> 
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="filter_search" class="form-control form-control-sm filter_search" 
                                       placeholder="Cari nama/kode barang">
                                <small class="form-text text-muted">Nama atau Kode Barang</small>
                            </div>
                        </div> 
                    </div> 
                </div> 
            </div> 
            
            @if(session('success')) 
                <div class="alert alert-success">{{ session('success') }}</div> 
            @endif 
            @if(session('error')) 
                <div class="alert alert-danger">{{ session('error') }}</div> 
            @endif 
            
            <div class="row g-3" id="barang-container">
                <!-- Barang cards will be loaded here -->
            </div>
        </div> 
    </div> 
    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div> 
    <!-- Tambahkan modal detail setelah modal utama -->
    <div id="detailBarangModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Barang</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Gambar memenuhi lebar -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <img id="detailBarangFoto" src="" alt="Foto Barang" 
                                 class="img-fluid rounded" 
                                 style="width: 100%; max-height: 300px; object-fit: cover; border: 1px solid #dee2e6;">
                        </div>
                    </div>
                    
                    <!-- Tabel detail di bawah gambar -->
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th width="30%" class="bg-light">Kode Barang</th>
                                        <td id="detailBarangKode" class="align-middle">-</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Nama Barang</th>
                                        <td id="detailBarangNama" class="align-middle">-</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Harga</th>
                                        <td id="detailBarangHarga" class="align-middle">-</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Stok</th>
                                        <td id="detailBarangStok" class="align-middle">-</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Kategori</th>
                                        <td id="detailBarangKategori" class="align-middle">-</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Supplier</th>
                                        <td id="detailBarangSupplier" class="align-middle">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning"><a href="{{ url('/supplier') }}">Ke Halaman Supplier</a></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection 
 
@push('css')
<style>
    /* Tambahkan di bagian CSS Anda */
    #detailBarangModal .modal-body {
        padding: 20px;
    }

    #detailBarangModal .table {
        margin-bottom: 0;
    }

    #detailBarangModal .table th {
        white-space: nowrap;
    }

    #detailBarangFoto {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
    }
    
    /* Improved card styling */
    .barang-card {
        margin-bottom: 0;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(0,0,0,0.125);
    }
    
    .barang-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .barang-card-body {
        padding: 15px;
        flex-grow: 1;
    }
    
    .barang-card-title {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }
    
    .barang-card-text {
        margin-bottom: 5px;
        font-size: 0.9rem;
        color: #555;
    }
    
    .barang-card-footer {
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-top: 1px solid #eee;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    
    .barang-card-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }
    
    .barang-price {
        font-weight: bold;
        color: #28a745;
    }
    
    .barang-stock {
        font-weight: bold;
        color: #007bff;
    }
    
    .barang-category {
        color: #6c757d;
        font-style: italic;
        font-size: 0.85rem;
    }
    
    /* Responsive grid settings */
    #barang-container {
        margin-right: -12px;
        margin-left: -12px;
    }
    
    #barang-container > [class*="col-"] {
        padding-right: 12px;
        padding-left: 12px;
        margin-bottom: 24px;
    }
    
    /* Button styling */
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endpush

@push('js') 
<script> 
    function modalAction(url = ''){ 
        $('#myModal').load(url,function(){ 
            $('#myModal').modal('show'); 
        }); 
    } 

    // Fungsi yang lebih aman untuk menampilkan detail barang
    function showDetailBarang(barangData) {
        try {
            // Parse data barang yang sudah di-stringify
            const barang = typeof barangData === 'string' ? JSON.parse(barangData) : barangData;
            
            // Set foto barang
            const fotoUrl = barang.foto_barang 
                ? "{{ asset('storage/barang') }}/" + barang.foto_barang
                : "{{ asset('images/default-product.png') }}";
            
            $('#detailBarangFoto').attr('src', fotoUrl);
            $('#detailBarangKode').text(barang.kode_barang || '-');
            $('#detailBarangNama').text(barang.nama_barang || '-');
            $('#detailBarangHarga').text('Rp' + (barang.harga ? new Intl.NumberFormat('id-ID').format(barang.harga) : '0'));
            $('#detailBarangStok').text(barang.stok || '0');
            $('#detailBarangKategori').text(barang.kategori?.nama_kategori || '-');
            $('#detailBarangSupplier').text(barang.supplier?.nama_supplier || '-');
            
            $('#detailBarangModal').modal('show');
        } catch (e) {
            console.error('Error showing barang detail:', e);
            alert('Terjadi kesalahan saat menampilkan detail barang');
        }
    }

    // Update bagian pembuatan card
    function loadBarangCards() {
        const searchTerm = $('.filter_search').val().trim();
        const kategori = $('.filter_kategori').val();
        
        $.ajax({
            url: "{{ url('barang/list') }}",
            type: "POST",
            dataType: "json",
            data: {
                filter_kategori: kategori,
                filter_search: searchTerm
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const container = $('#barang-container');
                container.empty();
                
                if(response.data && response.data.length > 0) {
                    response.data.forEach((barang) => {
                        // Buat salinan objek barang tanpa circular references
                        const barangSafe = {
                            id_barang: barang.id_barang,
                            kode_barang: barang.kode_barang,
                            nama_barang: barang.nama_barang,
                            harga: barang.harga,
                            stok: barang.stok,
                            foto_barang: barang.foto_barang,
                            kategori: barang.kategori ? {
                                nama_kategori: barang.kategori.nama_kategori
                            } : null,
                            supplier: barang.supplier ? {
                                nama_supplier: barang.supplier.nama_supplier
                            } : null
                        };
                        
                        // Stringify dengan escape karakter khusus
                        const barangData = JSON.stringify(barangSafe)
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, "\\'");
                        
                        const cardHtml = `
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card barang-card">
                                    <div class="barang-card-body" onclick='showDetailBarang(${barangData})' style="cursor: pointer;">
                                        <h5 class="barang-card-title">${barang.nama_barang}</h5>
                                        <p class="barang-card-text"><strong>Kode:</strong> ${barang.kode_barang}</p>
                                        <p class="barang-card-text"><strong>Harga:</strong> <span class="barang-price">Rp${new Intl.NumberFormat('id-ID').format(barang.harga)}</span></p>
                                        <p class="barang-card-text"><strong>Stok:</strong> <span class="barang-stock">${barang.stok}</span></p>
                                        <p class="barang-card-text barang-category">${barang.kategori?.nama_kategori || '-'}</p>
                                    </div>
                                    <div class="barang-card-footer">
                                        <div class="barang-card-actions">
                                            <!-- Button Tambah Stok -->
                                            <button onclick="event.stopPropagation(); modalAction('{{ url('/supply/create_ajax') }}?id_barang=' + ${barang.id_barang})" 
                                                    class="btn btn-sm btn-primary" title="Tambah Stok">
                                                <i class="fa fa-plus"></i> Stok
                                            </button>   
                                            <button onclick="event.stopPropagation(); modalAction('{{ url('/barang') }}/${barang.id_barang}/edit_ajax')" 
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button onclick="event.stopPropagation(); modalAction('{{ url('/barang') }}/${barang.id_barang}/delete_ajax')" 
                                                    class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <button onclick="event.stopPropagation(); showDetailBarang(${JSON.stringify(barang).replace(/"/g, '&quot;')})" 
                                                    class="btn btn-sm btn-info" title="Detail">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.append(cardHtml);
                    });
                } else {
                    container.append('<div class="col-12 text-center py-4"><p class="text-muted">Tidak ada data barang</p></div>');
                }
            },
            error: function(xhr) {
                console.error('Error loading barang:', xhr);
                alert('Gagal memuat data barang');
            }
        });
    }
 
    $(document).ready(function(){ 
        // Ambil parameter filter_kategori dari URL jika ada
        const urlParams = new URLSearchParams(window.location.search);
        const filterKategori = urlParams.get('filter_kategori');
        
        // Jika ada parameter filter_kategori, set nilai dropdown
        if (filterKategori) {
            $('.filter_kategori').val(filterKategori);
        }
        
        loadBarangCards();
        
        // Event untuk filter kategori
        $('.filter_kategori').change(function(){ 
            loadBarangCards();
        });
        
        // Event untuk filter search dengan debounce 300ms
        let searchTimeout;
        $('.filter_search').on('input', function(){
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function(){
                loadBarangCards();
            }, 300);
        });
    }); 
</script> 
@endpush