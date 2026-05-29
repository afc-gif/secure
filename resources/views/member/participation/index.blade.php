<x-dashboard.shell title="Participation status" eyebrow="Ownership Ledger">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[0.7fr_1.3fr]">
        <section class="cca-card p-6">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Cooperative Access Status</p>
            <h2 class="mt-3 text-3xl font-black text-emerald-300">{{ $participations->where('participation_status', 'active')->count() }}</h2>
            <p class="mt-3 text-sm leading-6 text-slate-400">Active ownership cycle participation records connected to your synchronized member identity.</p>
            <div class="mt-6 grid gap-3">
                @foreach (['Token Validated', 'Batch Joined', 'Milestone Tracking', 'Settlement Stage'] as $index => $label)
                    <div class="flex items-center gap-3 rounded-lg border {{ $index < 2 && $participations->isNotEmpty() ? 'border-emerald-300/20 bg-emerald-300/10' : 'border-white/10 bg-white/[0.04]' }} p-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $index < 2 && $participations->isNotEmpty() ? 'bg-emerald-300 text-[#08100c]' : 'bg-white/10 text-slate-400' }} text-xs font-black">{{ $index + 1 }}</span>
                        <span class="text-sm font-semibold text-white">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        <x-ownership.table :participations="$participations" />
    </div>
</x-dashboard.shell>
