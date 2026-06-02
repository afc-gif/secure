@props(['title', 'eyebrow' => 'CCA Portal'])

@php
    $user = auth()->user();
    $navigation = $user?->isAdmin()
        ? [
            ['label' => 'Command Overview', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard')],
            ['label' => 'Partner Registry', 'href' => route('admin.partners.index'), 'active' => request()->routeIs('admin.partners.*')],
            ['label' => 'Batch Cycles', 'href' => route('admin.batches.index'), 'active' => request()->routeIs('admin.batches.*')],
            ['label' => 'Access Tokens', 'href' => route('admin.tokens.index'), 'active' => request()->routeIs('admin.tokens.*')],
            ['label' => 'Contributions', 'href' => route('admin.contributions.index'), 'active' => request()->routeIs('admin.contributions.*')],
        ]
        : [
            ['label' => 'Ownership Home', 'href' => route('member.dashboard'), 'active' => request()->routeIs('member.dashboard')],
            ['label' => 'Active Cycles', 'href' => route('member.batches.index'), 'active' => request()->routeIs('member.batches.*')],
            ['label' => 'Access Token', 'href' => route('member.access-token.create'), 'active' => request()->routeIs('member.access-token.*')],
            ['label' => 'Participation', 'href' => route('member.participation.index'), 'active' => request()->routeIs('member.participation.*')],
            ['label' => 'Contributions', 'href' => route('member.contributions.index'), 'active' => request()->routeIs('member.contributions.*')],
            ['label' => 'Profile', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.*')],
        ];
    $unreadNotifications = $user?->unreadNotifications()->latest()->take(5)->get() ?? collect();
@endphp

<x-app-layout>
    <div class="min-h-screen lg:grid lg:grid-cols-[18rem_1fr]">
        <aside class="border-b border-white/10 bg-black/20 px-5 py-5 backdrop-blur-xl lg:min-h-screen lg:border-b-0 lg:border-r lg:py-7">
            <div class="flex items-center justify-between lg:block">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <x-application-logo class="h-11 w-11" />
                    <div>
                        <p class="text-sm font-black tracking-[0.26em] text-white">CCA</p>
                        <p class="text-xs text-slate-500">Country Culture Acres</p>
                    </div>
                </a>
                <span class="rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-xs font-semibold text-emerald-200 lg:mt-8 lg:inline-flex">{{ ucfirst($user->role) }}</span>
            </div>

            <nav class="mt-6 flex gap-2 overflow-x-auto lg:mt-10 lg:block lg:space-y-2">
                @foreach ($navigation as $item)
                    <a href="{{ $item['href'] }}" @class([
                        'block min-w-max rounded-lg px-4 py-3 text-sm font-semibold transition',
                        'border border-emerald-300/25 bg-emerald-300/10 text-emerald-100 shadow-glow' => $item['active'],
                        'text-slate-400 hover:bg-white/[0.06] hover:text-slate-100' => ! $item['active'],
                    ])>{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-8 hidden lg:block">
                @csrf
                <button class="cca-muted-button w-full">Sign Out</button>
            </form>
        </aside>

        <section class="min-w-0">
            <header class="sticky top-0 z-10 border-b border-white/10 bg-[#070b0a]/80 px-5 py-4 backdrop-blur-xl sm:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d4af62]">{{ $eyebrow }}</p>
                        <h1 class="mt-1 text-2xl font-black text-white sm:text-3xl">{{ $title }}</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-dashboard.notification-bell :notifications="$unreadNotifications" />
                        <div class="text-right">
                            <p class="text-sm font-bold text-white">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $user->reference_token }}</p>
                        </div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-lg border border-white/10 bg-white/[0.06] text-sm font-black text-emerald-200">{{ Str::of($user->name)->substr(0, 1)->upper() }}</div>
                    </div>
                </div>
            </header>

            <main class="px-5 py-6 sm:px-8 lg:py-8">
                {{ $slot }}
            </main>
        </section>
    </div>
</x-app-layout>
