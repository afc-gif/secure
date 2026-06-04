@props(['title', 'eyebrow' => 'CCA Portal'])

@php
    $user = auth()->user();
    $memberDashboardUnlocked = $user?->hasUnlockedDashboard() ?? true;
    $memberNavigation = [
        ['label' => 'Overview', 'href' => route('member.dashboard'), 'active' => request()->routeIs('member.dashboard'), 'icon' => 'dashboard', 'locked' => false],
        ['label' => 'VIP Token', 'href' => route('member.access-token.create'), 'active' => request()->routeIs('member.access-token.*'), 'icon' => 'key', 'locked' => false],
        ['label' => 'Active Cycles', 'href' => route('member.batches.index'), 'active' => request()->routeIs('member.batches.*'), 'icon' => 'calendar', 'locked' => ! $memberDashboardUnlocked],
        ['label' => 'Participation', 'href' => route('member.participation.index'), 'active' => request()->routeIs('member.participation.*'), 'icon' => 'chart', 'locked' => ! $memberDashboardUnlocked],
        ['label' => 'Contributions', 'href' => route('member.contributions.index'), 'active' => request()->routeIs('member.contributions.*'), 'icon' => 'ledger', 'locked' => ! $memberDashboardUnlocked],
        ['label' => 'Profile', 'href' => route('profile.edit'), 'active' => request()->routeIs('profile.*'), 'icon' => 'user', 'locked' => false],
        ['label' => 'Contact Us', 'href' => route('member.contact'), 'active' => request()->routeIs('member.contact'), 'icon' => 'mail', 'locked' => ! $memberDashboardUnlocked],
    ];

    $navigation = $user?->isAdmin()
        ? [
            ['label' => 'Command Overview', 'href' => route('admin.dashboard'), 'active' => request()->routeIs('admin.dashboard'), 'icon' => 'dashboard'],
            ['label' => 'Partner Registry', 'href' => route('admin.partners.index'), 'active' => request()->routeIs('admin.partners.*'), 'icon' => 'users'],
            ['label' => 'Batch Cycles', 'href' => route('admin.batches.index'), 'active' => request()->routeIs('admin.batches.*'), 'icon' => 'calendar'],
            ['label' => 'Secure Tokens', 'href' => route('admin.tokens.index'), 'active' => request()->routeIs('admin.tokens.*'), 'icon' => 'key'],
            ['label' => 'Contributions', 'href' => route('admin.contributions.index'), 'active' => request()->routeIs('admin.contributions.*'), 'icon' => 'ledger'],
        ]
        : $memberNavigation;
    $unreadNotifications = $user?->unreadNotifications()->latest()->take(5)->get() ?? collect();
@endphp

<x-app-layout>
    <div class="min-h-screen" x-data="{ navOpen: false }">
        <aside :class="navOpen ? 'w-72' : 'w-20'" class="fixed inset-y-0 left-0 z-30 border-r border-white/[0.07] bg-[#07080b]/95 shadow-2xl shadow-black/30 backdrop-blur-2xl transition-[width] duration-300">
            <div class="flex h-full flex-col overflow-hidden px-3 py-4">
                <div class="flex items-center gap-3">
                    <button type="button" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-white/[0.08] bg-white/[0.035] text-slate-100 transition hover:border-white/15 hover:bg-white/[0.07]" x-on:click="navOpen = ! navOpen" :aria-expanded="navOpen.toString()" aria-label="Toggle navigation">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex min-w-0 items-center gap-3" x-show="navOpen" x-transition.opacity>
                        <x-application-logo class="h-10 w-10 shrink-0" />
                        <div class="min-w-0">
                            <p class="text-sm font-black tracking-[0.22em] text-white">CCA</p>
                            <p class="truncate text-xs text-slate-500">Country Culture Acres</p>
                        </div>
                    </a>
                </div>

                <nav class="mt-7 flex-1 space-y-2">
                    @foreach ($navigation as $item)
                        @php($isLocked = $item['locked'] ?? false)
                        <a href="{{ $isLocked ? route('member.dashboard') : $item['href'] }}" title="{{ $isLocked ? $item['label'].' locked until VIP token activation' : $item['label'] }}" @class([
                            'group flex h-12 items-center gap-3 rounded-lg border px-3 text-sm font-semibold transition',
                            'border-[#f35aa5]/25 bg-[#f35aa5]/12 text-white shadow-lg shadow-black/20' => $item['active'] && ! $isLocked,
                            'border-transparent text-slate-400 hover:border-white/[0.08] hover:bg-white/[0.045] hover:text-slate-100' => ! $item['active'] && ! $isLocked,
                            'cursor-not-allowed border-transparent text-slate-600 opacity-70' => $isLocked,
                        ])>
                            <span class="flex h-7 w-7 shrink-0 items-center justify-center">
                                @switch($item['icon'])
                                    @case('calendar')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7 3v4M17 3v4M4 9h16M6 5h12a2 2 0 0 1 2 2v13H4V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        @break
                                    @case('key')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 8a5 5 0 1 0-2.7 4.43L15 16h3v3h3v-3.9l-5.15-5.15A5 5 0 0 0 14 8Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /><path d="M7 8h.01" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" /></svg>
                                        @break
                                    @case('chart')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 19V5M5 19h16M9 16v-5M13 16V8M17 16v-3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        @break
                                    @case('ledger')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 4h12v16H6zM9 8h6M9 12h6M9 16h3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        @break
                                    @case('user')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM4.5 20a7.5 7.5 0 0 1 15 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        @break
                                    @case('mail')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16v13H4zM5 7l7 6 7-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        @break
                                    @case('users')
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM2.5 20a6.5 6.5 0 0 1 13 0M17 11a3 3 0 0 0 0-6M18.5 20a5.5 5.5 0 0 0-3-4.9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                        @break
                                    @default
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 13h7V4H4zM13 20h7V4h-7zM4 20h7v-5H4z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                @endswitch
                            </span>
                            <span class="min-w-0 truncate" x-show="navOpen" x-transition.opacity>{{ $item['label'] }}</span>
                            @if ($isLocked)
                                <span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#d8bf7a]" x-show="navOpen" x-transition.opacity></span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button class="flex h-12 w-full items-center gap-3 rounded-lg border border-rose-400/35 bg-rose-500/15 px-3 text-sm font-black uppercase tracking-[0.12em] text-rose-100 shadow-lg shadow-black/20 transition hover:border-rose-300/60 hover:bg-rose-500/25 hover:text-white" title="Logout" aria-label="Logout">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-rose-500 text-white shadow-sm shadow-rose-950/40">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 3v8" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                <path d="M7.05 6.75a8 8 0 1 0 9.9 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <span x-show="navOpen" x-transition.opacity>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <section :class="navOpen ? 'ml-20 lg:ml-72' : 'ml-20'" class="min-h-screen transition-[margin] duration-300">
        <header class="sticky top-0 z-20 border-b border-white/[0.07] bg-[#07080b]/90 backdrop-blur-2xl">
            <div class="mx-auto max-w-[76rem] px-4 py-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">{{ $eyebrow }}</p>
                        <h1 class="mt-1 truncate text-xl font-black text-white sm:text-2xl">{{ $title }}</h1>
                    </div>
                    <div class="flex min-w-0 shrink-0 items-center gap-3">
                        <x-dashboard.notification-bell :notifications="$unreadNotifications" />
                        <div class="hidden min-w-0 text-right sm:block">
                            <p class="truncate text-sm font-bold text-white">{{ $user->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ $user->reference_token }}</p>
                        </div>
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-white/[0.08] bg-white/[0.035] text-sm font-black text-slate-100">{{ Str::of($user->name)->substr(0, 1)->upper() }}</div>
                    </div>
                </div>
            </div>
        </header>

        <main class="px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-8">
            <div class="mx-auto max-w-[76rem]">
                {{ $slot }}
            </div>
        </main>
        </section>
    </div>
</x-app-layout>
