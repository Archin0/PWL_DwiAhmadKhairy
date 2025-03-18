@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Welcome')
@section('content_header_title', 'Home')
@section('content_header_subtitle', 'Welcome')

{{-- Customize body: main page content --}}
@section('content_body')
    <p>Welcome to this beautiful admin panel</p>
@stop

{{-- Push extra CSS --}}
@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra CSS --}}
@push('js')
    {{-- Add here extra stylesheets --}}
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@endpush