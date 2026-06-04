<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
<body class="font-sans">
    <main class="flex min-h-screen items-center px-5 py-10">
        <section class="mx-auto grid w-full max-w-6xl gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
            <div>
                <a href="{{ route('login') }}" class="inline-flex items-center gap-3">
                    <x-application-logo class="h-12 w-12" />
                    <span class="font-black tracking-[0.28em] text-white">CCA PORTAL</span>
                </a>
                <h1 class="mt-10 max-w-3xl text-5xl font-black leading-tight text-white sm:text-6xl">Country Culture Acres private ownership portal.</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">A secure agritech co-ownership ecosystem for vetted partners participating in countryside agricultural growth cycles.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('login') }}" class="cca-button">Login</a>
                    <a href="{{ route('register') }}" class="cca-muted-button">Signup</a>
                </div>
            </div>

            <div class="cca-panel rounded-lg p-5 shadow-glow">
                <div class="grid gap-4">
                    <div class="cca-card p-5">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-500">Platform Layer</p>
                        <p class="mt-3 text-2xl font-black text-emerald-300">Secure Member Onboarding</p>
                    </div>
                    <div class="cca-card p-5">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-500">Ownership Layer</p>
                        <p class="mt-3 text-2xl font-black text-[#d4af62]">Batch Cycle Visibility</p>
                    </div>
                    <div class="cca-card p-5">
                        <p class="text-xs uppercase tracking-[0.22em] text-slate-500">Access Layer</p>
                        <p class="mt-3 text-2xl font-black text-white">Admin and Partner Workspaces</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @if($jsFile)
        <script src="/build/{{ $jsFile }}"></script>
    @endif
</body>
</html>
