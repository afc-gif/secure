@props(['status'])

@php
    $tone = match ($status) {
        'active', 'used', 'completed' => 'border-emerald-300/20 bg-emerald-300/10 text-emerald-200',
        'upcoming', 'pending' => 'border-[#d4af62]/30 bg-[#d4af62]/10 text-[#d4af62]',
        'revoked', 'locked', 'suspended' => 'border-rose-300/25 bg-rose-300/10 text-rose-200',
        default => 'border-white/10 bg-white/[0.06] text-slate-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex max-w-full rounded-md border px-2.5 py-1 text-xs font-bold uppercase tracking-[0.1em] {$tone}"]) }}>
    {{ str_replace('_', ' ', $status) }}
</span>
