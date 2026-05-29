@props(['batch'])

<div {{ $attributes }}>
    <div class="flex items-center justify-between gap-4 text-xs font-bold uppercase tracking-[0.18em]">
        <span class="text-slate-500">Cycle Access</span>
        <span class="text-[#d4af62]">{{ $batch->current_members }}/{{ $batch->max_members ?: 'Open' }}</span>
    </div>
    <div class="mt-3 h-2 overflow-hidden rounded-full bg-white/10">
        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-[#d4af62]" style="width: {{ $batch->progressPercentage() }}%"></div>
    </div>
</div>
