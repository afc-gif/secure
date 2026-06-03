@props(['batch'])

<div {{ $attributes }}>
    <div class="flex items-center justify-between gap-4 text-xs font-bold uppercase tracking-[0.12em]">
        <span class="text-slate-500">Cycle Access</span>
        <span class="text-[#ffd4e9]">{{ $batch->current_members }}/{{ $batch->max_members ?: 'Open' }}</span>
    </div>
    <div class="mt-3 h-2 overflow-hidden rounded-full bg-white/10">
        <div class="h-full rounded-full bg-gradient-to-r from-[#7c3cff] via-[#d936ad] to-[#f35f8d]" style="width: {{ $batch->progressPercentage() }}%"></div>
    </div>
</div>
