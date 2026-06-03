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
    <div class="min-h-screen lg:grid lg:grid-cols-[17rem_1fr]">
        <aside class="border-b border-white/[0.07] bg-[#07080a]/90 px-4 py-4 backdrop-blur-xl sm:px-5 sm:py-5 lg:min-h-screen lg:border-b-0 lg:border-r lg:py-7">
            <div class="flex items-center justify-between lg:block">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <x-application-logo class="h-10 w-10" />
                    <div>
                        <p class="text-sm font-black tracking-[0.24em] text-white">CCA</p>
                        <p class="text-xs text-slate-500">Country Culture Acres</p>
                    </div>
                </a>
                <span class="rounded-md border border-white/[0.08] bg-white/[0.03] px-3 py-1 text-xs font-semibold text-slate-300 lg:mt-8 lg:inline-flex">{{ ucfirst($user->role) }}</span>
            </div>

            <nav class="mt-4 flex gap-2 overflow-x-auto pb-1 sm:mt-6 lg:mt-10 lg:block lg:space-y-2 lg:pb-0">
                @foreach ($navigation as $item)
                    <a href="{{ $item['href'] }}" @class([
                        'block min-w-max rounded-md px-3.5 py-2 text-sm font-semibold transition sm:px-4 sm:py-2.5',
                        'border border-white/10 bg-white/[0.06] text-slate-100 shadow-sm shadow-black/20' => $item['active'],
                        'text-slate-400 hover:bg-white/[0.04] hover:text-slate-100' => ! $item['active'],
                    ])>{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="mt-8 hidden lg:block">
                @csrf
                <button class="cca-muted-button w-full">Sign Out</button>
            </form>
        </aside>

        <section class="min-w-0">
            <header class="sticky top-0 z-10 border-b border-white/[0.07] bg-[#08090b]/88 px-4 py-4 backdrop-blur-xl sm:px-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">{{ $eyebrow }}</p>
                        <h1 class="mt-1 text-2xl font-extrabold text-white sm:text-3xl">{{ $title }}</h1>
                    </div>
                    <div class="flex items-center justify-between gap-3 sm:justify-end">
                        <x-dashboard.notification-bell :notifications="$unreadNotifications" />
                        <div class="min-w-0 flex-1 text-right sm:flex-none">
                            <p class="text-sm font-bold text-white">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $user->reference_token }}</p>
                        </div>
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md border border-white/[0.08] bg-white/[0.03] text-sm font-black text-slate-200">{{ Str::of($user->name)->substr(0, 1)->upper() }}</div>
                    </div>
                </div>
            </header>

            <main class="px-4 py-4 sm:px-8 sm:py-6 lg:py-8">
                {{ $slot }}
            </main>
        </section>
    </div>
</x-app-layout>
