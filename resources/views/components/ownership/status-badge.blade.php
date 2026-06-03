@props(['status'])

@php
    $tone = match ($status) {
        'active', 'used', 'completed' => 'border-[#f35aa5]/25 bg-[#f35aa5]/10 text-[#ffd4e9]',
        'upcoming', 'pending' => 'border-[#f35f8d]/25 bg-[#f35f8d]/10 text-[#ffd0bf]',
        'revoked', 'locked', 'suspended' => 'border-rose-300/25 bg-rose-300/10 text-rose-200',
        default => 'border-white/10 bg-white/[0.06] text-slate-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex max-w-full rounded-md border px-2.5 py-1 text-xs font-bold uppercase tracking-[0.1em] {$tone}"]) }}>
    {{ str_replace('_', ' ', $status) }}
</span>
