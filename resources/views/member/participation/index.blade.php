<x-dashboard.shell title="Participation status" eyebrow="Ownership Ledger">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-[#f35aa5]/25 bg-[#f35aa5]/10 px-5 py-4 text-sm font-semibold text-[#ffd4e9]">{{ session('status') }}</div>
    @endif

    <div class="grid gap-5 xl:grid-cols-[0.7fr_1.3fr]">
        <section class="cca-card relative overflow-hidden p-6">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_18%_8%,rgba(217,54,173,0.16),transparent_18rem)]"></div>
            <div class="relative">
            <p class="cca-kicker">Secure Access Status</p>
            <h2 class="mt-3 text-3xl font-black text-[#ffd4e9]">{{ $participations->where('participation_status', 'active')->count() }}</h2>
            <p class="mt-3 text-sm leading-6 text-slate-400">Active ownership cycle participation records connected to your synchronized member identity.</p>
            <div class="mt-6 grid gap-3">
                @foreach (['Token Validated', 'Batch Joined', 'Milestone Tracking', 'Settlement Stage'] as $index => $label)
                    <div class="flex items-center gap-3 rounded-lg border {{ $index < 2 && $participations->isNotEmpty() ? 'border-[#f35aa5]/25 bg-[#f35aa5]/10' : 'border-white/[0.07] bg-[#0b0d10]/70' }} p-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $index < 2 && $participations->isNotEmpty() ? 'bg-[#f35aa5] text-white' : 'bg-white/10 text-slate-400' }} text-xs font-black">{{ $index + 1 }}</span>
                        <span class="text-sm font-semibold text-white">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
            </div>
        </section>

        <x-ownership.table :participations="$participations" />
    </div>
</x-dashboard.shell>
