<!DOCTYPE html>
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
</html>
