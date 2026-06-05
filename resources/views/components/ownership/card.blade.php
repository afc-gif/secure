@props(['batch', 'action' => null])

@php
    $allocationLabel = $batch->batch_code === 'SECURE-GROUNDS-03' ? 'Ledger Privilege Cap' : 'Portfolio Allocation';
@endphp

<section {{ $attributes->merge(['class' => 'cca-card min-w-0 overflow-hidden p-4 sm:p-5']) }}>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">
                <x-ownership.status-badge :status="$batch->status" />
            </div>
            <h3 class="mt-4 text-xl font-black text-white">{{ $batch->title }}</h3>
            <p class="mt-2 text-sm leading-6 text-slate-400">{{ $batch->description ?: 'Structured cooperative ownership cycle.' }}</p>
        </div>
        <div class="w-full rounded-lg border border-white/[0.07] bg-[#0b0d10]/70 px-4 py-3 text-left sm:w-auto sm:text-right">
            <p class="text-xs uppercase tracking-[0.14em] text-slate-500">Batch Code</p>
            <p class="mt-1 break-all font-mono text-sm font-black text-white">{{ $batch->batch_code }}</p>
        </div>
    </div>
    <div class="mt-5 grid gap-3 sm:grid-cols-3">
        <div class="rounded-lg border border-white/[0.07] bg-white/[0.025] p-3">
            <p class="text-xs uppercase tracking-[0.14em] text-slate-500">Window</p>
            <p class="mt-1 text-sm font-semibold text-white">{{ $batch->start_date?->format('M j') ?? 'Active' }} - {{ $batch->end_date?->format('M j, Y') ?? 'Continuous' }}</p>
        </div>
        <div class="rounded-lg border border-white/[0.07] bg-white/[0.025] p-3">
            <p class="text-xs uppercase tracking-[0.14em] text-slate-500">Ownership Level</p>
            <p class="mt-1 text-sm font-semibold text-white">{{ Str::of($batch->ownership_level)->replace('_', ' ')->title() }}</p>
        </div>
        <div class="rounded-lg border border-white/[0.07] bg-white/[0.025] p-3">
            <p class="text-xs uppercase tracking-[0.14em] text-slate-500">{{ $allocationLabel }}</p>
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
