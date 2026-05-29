<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CCA Portal') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
        $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
    @endphp
    @if($cssFile)
        <link rel="stylesheet" href="/build/{{ $cssFile }}">
    @endif
</head>
<body class="min-h-screen font-sans">
    <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
        <div class="absolute inset-x-8 top-8 h-px bg-gradient-to-r from-transparent via-emerald-300/30 to-transparent"></div>
        <div class="grid w-full max-w-6xl gap-8 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
            <section class="hidden lg:block">
                <div class="max-w-md">
                    <a href="/" class="inline-flex items-center gap-3">
                        <x-application-logo class="h-11 w-11" />
                        <span class="text-lg font-bold tracking-[0.28em] text-slate-100">CCA PORTAL</span>
                    </a>
                    <h1 class="mt-10 text-5xl font-black leading-tight text-white">Private countryside ownership, governed with precision.</h1>
                    <p class="mt-5 text-lg leading-8 text-slate-300">Secure onboarding, cooperative access, and ownership-cycle visibility for vetted Country Culture Acres partners.</p>
                    <div class="mt-8 grid grid-cols-3 gap-3">
                        <div class="cca-card p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Access</p>
                            <p class="mt-2 text-2xl font-bold text-emerald-300">Private</p>
                        </div>
                        <div class="cca-card p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Ledger</p>
                            <p class="mt-2 text-2xl font-bold text-[#d4af62]">Tracked</p>
                        </div>
                        <div class="cca-card p-4">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Cycles</p>
                            <p class="mt-2 text-2xl font-bold text-slate-100">Active</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mx-auto w-full max-w-md">
                {{ $slot }}
            </section>
        </div>
    </main>
    @if($jsFile)
        <script src="/build/{{ $jsFile }}"></script>
    @endif
</body>
</html>
