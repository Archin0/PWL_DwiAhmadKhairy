{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-red-500 p-4 text-white">
        <ul class="flex space-x-4">
            <li><a href="{{ url('/') }}" class="hover:underline">Home</a></li>
            <li><a href="{{ url('/products') }}" class="hover:underline">Products</a></li>
            <li><a href="{{ url('/sales') }}" class="hover:underline">Sales</a></li>
            <li><a href="{{ url('/user/2341720073/name/Dwi Ahmad Khairy') }}" class="hover:underline">User Profile</a></li>
        </ul>
    </nav>

    <div class="container mx-auto p-6">
        @yield('content')
    </div>

    <footer class="bg-gray-700 text-white p-4 text-center mt-6">
        &copy; 2025 POS DWIK - All Rights Reserved
    </footer>
</body>
</html> --}}

@extends('adminlte::page') 
 
{{-- Extend and customize the browser title --}} 
 
@section('title') 
    {{ config('adminlte.title') }} 
    @hasSection('subtitle') | @yield('subtitle') @endif 
@stop 

@vite('resources/js/app.js')

{{-- Extend and customize the page content header --}} 
 
@section('content_header') 
    @hasSection('content_header_title') 
        <h1 class="text-muted"> 
            @yield('content_header_title') 
 
            @hasSection('content_header_subtitle') 
                <small class="text-dark"> 
                    <i class="fas fa-xs fa-angle-right text-muted"></i> 
                    @yield('content_header_subtitle') 
                </small> 
            @endif 
        </h1> 
    @endif 
@stop 
 
{{-- Rename section content to content_body --}} 
 
@section('content') 
    @yield('content_body') 
@stop 
 
{{-- Create a common footer --}} 
 
@section('footer') 
    <div class="float-right"> 
        Version: {{ config('app.version', '1.0.0') }} 
    </div> 
 
    <strong> 
        <a href="{{ config('app.company_url', '#') }}"> 
            {{ config('app.company_name', 'My company') }} 
        </a> 
    </strong> 
@stop 
 
{{-- Add common Javascript/Jquery code --}} 
 
@push('js') 
<script src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script> 
@endpush 

@stack('scripts')
 
{{-- Add common CSS customizations --}} 
 
@push('css')
<link 
rel="stylesheet" 
href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css" />

<style type="text/css"> 
 
    {{-- You can add AdminLTE customizations here --}} 
    /* 
    .card-header { 
        border-bottom: none; 
    } 
    .card-title { 
        font-weight: 600; 
    } 
    */ 
</style> 
@endpush