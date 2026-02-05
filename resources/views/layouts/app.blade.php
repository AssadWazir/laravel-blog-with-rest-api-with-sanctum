<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                background-color: #f8f9fa;
                font-family: 'Figtree', sans-serif;
            }
            .navbar {
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                background-color: #ffffff !important;
            }
            .navbar-brand {
                font-weight: 600;
                color: #2c3e50 !important;
            }
            .nav-link {
                color: #555 !important;
                font-weight: 500;
                transition: color 0.3s ease;
            }
            .nav-link:hover {
                color: #007bff !important;
            }
            main {
                min-height: calc(100vh - 100px);
                padding: 40px 0;
            }
            .card {
                border: none;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            }
            .form-control, .form-select {
                border-radius: 0.375rem;
                border: 1px solid #dee2e6;
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }
            .form-control:focus, .form-select:focus {
                border-color: #007bff;
                box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            }
            .btn {
                padding: 0.6rem 1.2rem;
                font-weight: 500;
                border-radius: 0.375rem;
                transition: all 0.3s ease;
            }
            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }
            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }
            .btn-success {
                background-color: #28a745;
                border-color: #28a745;
            }
            .btn-success:hover {
                background-color: #218838;
                border-color: #218838;
            }
            .btn-danger {
                background-color: #dc3545;
                border-color: #dc3545;
            }
            .btn-danger:hover {
                background-color: #c82333;
                border-color: #c82333;
            }
            .alert {
                border-radius: 0.375rem;
                border: none;
                padding: 1rem 1.25rem;
            }
            .table {
                border-collapse: separate;
                border-spacing: 0;
            }
            .table thead th {
                background-color: #f8f9fa;
                font-weight: 600;
                border-bottom: 2px solid #dee2e6;
                padding: 1rem;
            }
            .table tbody td {
                padding: 0.875rem 1rem;
                vertical-align: middle;
            }
            .table tbody tr:hover {
                background-color: #f8f9fa;
            }
            h1, h2, h3, h4, h5, h6 {
                color: #2c3e50;
                font-weight: 600;
            }
            .text-muted {
                color: #6c757d !important;
            }
        </style>
    </head>
    <body>
        <div class="d-flex flex-column min-vh-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @hasSection('header')
                <header class="bg-white border-bottom">
                    <div class="container-fluid py-4 px-4">
                        @yield('header')
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-grow-1">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-top mt-5">
                <div class="container-fluid py-4 px-4 text-center text-muted">
                    <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                </div>
            </footer>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
