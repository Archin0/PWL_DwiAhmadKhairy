@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Profil Pengguna</h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- Kolom Foto Profil -->
                        <div class="col-md-4 text-center">
                            <div class="profile-image-container mb-4">
                                @if($user->foto_profil)
                                    <img src="{{ asset('storage/profile/' . $user->foto_profil) }}" 
                                         alt="Foto Profil" 
                                         class="img-thumbnail rounded-circle profile-image">
                                @else
                                    <img src="{{ asset('default-avatar.jpg') }}" 
                                         alt="Foto Profil" 
                                         class="img-thumbnail rounded-circle profile-image">
                                @endif
                            </div>
                            
                            <div class="user-status mt-3">
                                <span class="badge
                                    @if($user->level->nama_level === 'Administrator') badge-success 
                                    @elseif($user->level->nama_level === 'Staff') badge-warning 
                                    @else badge-primary 
                                    @endif">
                                    {{ ucfirst($user->level->nama_level) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Kolom Informasi User -->
                        <div class="col-md-8">
                            <div class="user-info">
                                <h2 class="text-primary">{{ $user->nama }}</h2>
                                <p class="text-muted">{{ $user->username }}</p>
                                
                                <hr class="my-4">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-4">
                                            <h5 class="info-title">Terakhir Diperbarui</h5>
                                            <p class="info-value">
                                                {{ $user->updated_at->format('d F Y H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="{{ route('profile.edit') }}" class="btn btn-primary mr-2">
                                        <i class="fas fa-edit"></i> Edit Profil
                                    </a>
                                    
                                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .profile-image-container {
        position: relative;
        display: inline-block;
    }
    
    .profile-image {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border: 5px solid #f8f9fa;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .profile-image:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .info-title {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.3rem;
    }
    
    .info-value {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0;
    }
    
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .card-header {
        border-radius: 0 !important;
    }
    
    .user-status .badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }
</style>
@endpush

@push('js')
<script>
    $(document).ready(function() {
        // Animasi hover untuk tombol
        $('.btn').hover(
            function() {
                $(this).css('transform', 'translateY(-2px)');
            },
            function() {
                $(this).css('transform', 'translateY(0)');
            }
        );
    });
</script>
@endpush