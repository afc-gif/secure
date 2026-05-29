@props(['batch', 'action' => null])

<section {{ $attributes->merge(['class' => 'cca-card p-5']) }}>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <x-ownership.status-badge :status="$batch->status" />
                @if ($batch->is_active)
                    <x-profile.status-badge>Open</x-profile.status-badge>
                @endif
            </div>
            <h3 class="mt-4 text-xl font-black text-white">{{ $batch->title }}</h3>
            <p class="mt-2 text-sm leading-6 text-slate-400">{{ $batch->description ?: 'Structured cooperative ownership cycle.' }}</p>
        </div>
        <div class="rounded-lg border border-[#d4af62]/20 bg-[#d4af62]/10 px-4 py-3 text-right">
            <p class="text-xs uppercase tracking-[0.2em] text-[#d4af62]">Batch Code</p>
            <p class="mt-1 font-mono text-sm font-black text-white">{{ $batch->batch_code }}</p>
        </div>
    </div>
    <div class="mt-5 grid gap-4 sm:grid-cols-3">
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Window</p>
            <p class="mt-1 text-sm font-semibold text-white">{{ $batch->start_date?->format('M j') ?? 'Open' }} - {{ $batch->end_date?->format('M j, Y') ?? 'Continuous' }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Ownership Level</p>
            <p class="mt-1 text-sm font-semibold text-white">{{ Str::of($batch->ownership_level)->replace('_', ' ')->title() }}</p>
        </div>
        <div>
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Participation Fee</p>
            <p class="mt-1 text-sm font-semibold text-white">{{ $batch->participation_fee ? 'USD '.number_format((float) $batch->participation_fee, 2) : 'Not set' }}</p>
        </div>
    </div>
    <x-ownership.progress :batch="$batch" class="mt-5" />
    @if ($action)
        <div class="mt-5 border-t border-white/10 pt-5">
            {{ $action }}
        </div>
    @endif
</section>
