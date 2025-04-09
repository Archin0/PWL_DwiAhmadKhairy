@extends('layouts.template') 

@section('content')
<div class="container">

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}">
            @error('nama')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Foto Profil</label><br>
            @if ($user->profile_photo)
                <img src="{{ asset('storage/profile/' . $user->profile_photo) }}" alt="Foto Profil" width="300" class="mb-2">
            @else
                <img src="{{ asset('default-avatar.jpg') }}" alt="Foto Profil" width="300" class="mb-2"><br>
                <p class="text-red">Kamu masih menggunakan avatar default !</p>
            @endif
            
            <input type="file" name="profile_photo" class="form-control-file">
            @error('profile_photo')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button class="btn btn-primary" type="submit">Simpan</button><br><br>
    </form>
</div>
@endsection
