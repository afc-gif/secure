@props(['step', 'completion'])

<x-app-layout>
    <div class="min-h-screen px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-6xl">
            <header class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <x-application-logo class="h-12 w-12" />
                    <div>
                        <p class="text-sm font-black tracking-[0.26em] text-white">CCA</p>
                        <p class="text-xs text-slate-500">Secure Ownership Intake</p>
                    </div>
                </a>
                <div class="flex items-center gap-3">
                    <x-profile.status-badge tone="gold">Private Registry</x-profile.status-badge>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="cca-muted-button">Sign Out</button>
                    </form>
                </div>
            </header>

            <section class="mt-8 cca-card p-5 sm:p-7">
                <div class="grid gap-6 lg:grid-cols-[1fr_18rem] lg:items-end">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d4af62]">Country Culture Acres</p>
                        <h1 class="mt-3 text-3xl font-black text-white sm:text-4xl">Member Onboarding & Identity Synchronization</h1>
                        <p class="mt-4 max-w-3xl text-sm leading-6 text-slate-400">Complete your cooperative ownership profile before entering the member workspace.</p>
                    </div>
                    <x-onboarding.progress-bar :value="$completion" />
                </div>
            </section>

            <div class="mt-6">
                <x-onboarding.step-indicator :step="$step" />
            </div>

            @if (session('status'))
                <div class="mt-6 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mt-6" x-data="{ loading: false, loadingText: 'Synchronizing Cooperative Identity...' }">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-app-layout>
