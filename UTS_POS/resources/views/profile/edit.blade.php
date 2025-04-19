@extends('layouts.template') 

@section('content')
<div class="container">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
            @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Password Lama (Diisi hanya jika ingin mengubah password)</label>
            <input type="password" name="current_password" class="form-control">
            @error('current_password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="new_password" class="form-control">
            @error('new_password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" class="form-control">
        </div>

        <div class="form-group">
            <label>Foto Profil</label><br>
            @if ($user->foto_profil)
                <img src="{{ asset('storage/profile/' . $user->foto_profil) }}" alt="Foto Profil" width="300" class="mb-2">
            @else
                <img src="{{ asset('default-avatar.jpg') }}" alt="Foto Profil" width="300" class="mb-2"><br>
                <p class="text-red">Kamu masih menggunakan avatar default!</p>
            @endif
            
            <input type="file" name="foto_profil" class="form-control-file">
            @error('foto_profil')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary" type="submit">Simpan</button><br><br>
    </form>
</div>
@endsection