@extends('layouts.template')
 
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header d-flex justify-content-center align-items-center">
        <div class="card-tools d-flex justify-content-center">
            <button onclick="modalAction('{{ url('/kategori/create_ajax') }}')" 
                    class="btn btn-lg btn-success mt-1">
                Tambah Kategori
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
        
        <div class="row" id="kategori-container">
            <!-- Kategori cards will be loaded here -->
        </div>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" databackdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
<div id="detailModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img id="detailFoto" src="" alt="Foto Kategori" class="img-fluid rounded mb-3">
                    </div>
                    <div class="col-md-6">
                        <table class="table table-striped table-row-bordered">
                            <tr>
                                <th>Kode Kategori</th>
                                <td id="detailKode"></td>
                            </tr>
                            <tr>
                                <th>Nama Kategori</th>
                                <td id="detailNama"></td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td id="detailDeskripsi"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a id="detailBarangLink" href="#" class="btn btn-primary" target="_blank">Lihat Barang</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .kategori-card {
        width: 100%;
        margin-bottom: 20px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        display: flex;
        min-height: 180px; /* Minimum height untuk konsistensi */
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); /* Warna default */
    }

    /* Container gambar - 1/4 lebar card */
    .kategori-image-wrapper {
        width: 25%;
        min-width: 25%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255,255,255,0.3); /* Background transparan */
        padding: 10px;
        border-right: 1px solid rgba(0,0,0,0.1); /* Garis pemisah subtle */
    }

    /* Frame untuk gambar */
    .kategori-image-frame {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    /* Gambar itu sendiri */
    .kategori-image {
        max-height: 100%;
        max-width: 100%;
        width: auto;
        height: auto;
        object-fit: contain; /* Pastikan gambar utuh terlihat */
        transition: transform 0.3s ease;
    }

    /* Container konten - 3/4 lebar card */
    .kategori-content {
        width: 75%;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    /* Efek hover */
    .kategori-card:hover .kategori-image {
        transform: scale(1.05);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .kategori-card {
            flex-direction: column;
        }
        .kategori-image-wrapper {
            width: 100%;
            height: 150px;
            border-right: none;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        .kategori-content {
            width: 100%;
        }
    }
</style>
@endpush

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    function showDetailModal(kategori) {
        // Set modal content
        $('#detailFoto').attr('src', "{{ asset('storage/kategori') }}/" + kategori.foto_kategori);
        $('#detailKode').text(kategori.kode_kategori);
        $('#detailNama').text(kategori.nama_kategori);
        $('#detailDeskripsi').text(kategori.deskripsi || '-');
        $('#detailBarangLink').attr('href', "{{ url('barang') }}?filter_kategori=" + kategori.id_kategori);
        
        // Show modal
        $('#detailModal').modal('show');
    }

    // Array of colors for cards
    const cardColors = [
        'linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%)',
        'linear-gradient(135deg, #e0f7fa 0%, #80deea 100%)',
        'linear-gradient(135deg, #f3e5f5 0%, #ce93d8 100%)',
        'linear-gradient(135deg, #e8f5e9 0%, #a5d6a7 100%)',
        'linear-gradient(135deg, #fff3e0 0%, #ffcc80 100%)',
        'linear-gradient(135deg, #fce4ec 0%, #f48fb1 100%)'
    ];

    function loadKategoriCards() {
        $.ajax({
            url: "{{ url('kategori/list') }}",
            type: "POST",
            dataType: "json",
            success: function(response) {
                const container = $('#kategori-container');
                container.empty();
                
                if(response.data && response.data.length > 0) {
                    response.data.forEach((kategori, index) => {
                        const colorIndex = index % cardColors.length;
                        const cardHtml = `
                            <div class="col-md-12">
                                <div class="kategori-card d-flex" style="background: ${cardColors[colorIndex]}" onclick="showDetailModal(${JSON.stringify(kategori).replace(/"/g, '&quot;')})">
                                    <!-- Bagian Gambar (1/4) -->
                                    <div class="kategori-image-wrapper">
                                        <div class="kategori-image-frame">
                                            <img src="{{ asset('storage/kategori') }}/${kategori.foto_kategori}"
                                                alt="${kategori.nama_kategori}"
                                                class="kategori-image"
                                                onerror="this.src='{{ asset('images/default-car.png') }}'">
                                        </div>
                                    </div>
                                    
                                    <!-- Bagian Konten (3/4) -->
                                    <div class="kategori-content">
                                        <h5 class="kategori-title">${kategori.nama_kategori}</h5>
                                        <p class="mb-2"><strong>Kode:</strong> ${kategori.kode_kategori}</p>
                                        ${kategori.deskripsi ? `<p>${kategori.deskripsi}</p>` : ''}
                                        <div class="kategori-actions">
                                            <button onclick="event.stopPropagation(); modalAction('{{ url('/kategori/') }}/${kategori.id_kategori}/edit_ajax')" 
                                                    class="btn btn-sm btn-warning">
                                                <i class="fa fa-edit"></i> Edit
                                            </button>
                                            <button onclick="event.stopPropagation(); modalAction('{{ url('/kategori') }}/${kategori.id_kategori}/delete_ajax')" 
                                                    class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                            <button onclick="event.stopPropagation(); showDetailModal(${JSON.stringify(kategori).replace(/"/g, '&quot;')})" 
                                                    class="btn btn-sm btn-info">
                                                <i class="fa fa-eye"></i> Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.append(cardHtml);
                    });
                } else {
                    container.append('<div class="col-12 text-center py-4"><p>Tidak ada data kategori</p></div>');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: response.message
                });
            }
        });
    }

    $(document).ready(function() {
        loadKategoriCards();
    });
</script>
@endpush