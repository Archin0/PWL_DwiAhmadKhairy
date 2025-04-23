@extends('layouts.app')

@section('title', 'User Profile - POS System')

@section('content')
    <h1 class="text-3xl font-bold">User Profile</h1>
    <p class="mt-2">ID: {{ $id }}</p>
    <p>Name: {{ $name }}</p>
@endsection
