<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CCA Portal') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Debug: Check if manifest exists -->
    @if(file_exists(public_path('build/manifest.json')))
        <meta name="debug" content="manifest-exists">
        <!-- Try @vite() -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <meta name="debug" content="manifest-missing">
        <link rel="stylesheet" href="/build/assets/app-NGirFLSX.css">
        <script src="/build/assets/app-DO2nEFzp.js"></script>
    @endif
</head>
<body class="min-h-screen font-sans">
    {{ $slot }}
</body>
</html>
