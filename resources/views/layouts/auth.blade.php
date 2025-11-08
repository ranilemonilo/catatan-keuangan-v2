<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentikasi | Catatan Keuangan</title>

    <!-- Bootstrap Lokal -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css') }}">
    @livewireStyles
</head>
<body class="bg-light">

    <div class="container py-5">
        @yield('content')
    </div>

    <!-- Bootstrap JS Lokal -->
    <script src="{{ asset('assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js') }}"></script>
    @livewireScripts
</body>
</html>
