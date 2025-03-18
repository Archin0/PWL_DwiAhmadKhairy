@extends('layouts.app')

@section('title', 'Products - POS System')

@section('content')
    <h1 class="text-3xl font-bold">Daftar Produk</h1>
    <ul class="mt-4">
        <li><a href="{{ url('/category/food-beverage') }}" class="text-blue-500 hover:underline">Food & Beverage</a></li>
        <li><a href="{{ url('/category/beauty-health') }}" class="text-blue-500 hover:underline">Beauty & Health</a></li>
        <li><a href="{{ url('/category/home-care') }}" class="text-blue-500 hover:underline">Home Care</a></li>
        <li><a href="{{ url('/category/baby-kid') }}" class="text-blue-500 hover:underline">Baby & Kid</a></li>
    </ul>
@endsection
