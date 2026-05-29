@props(['tone' => 'emerald'])

@php
    $classes = [
        'emerald' => 'border-emerald-300/20 bg-emerald-300/10 text-emerald-200',
        'gold' => 'border-[#d4af62]/30 bg-[#d4af62]/10 text-[#d4af62]',
        'slate' => 'border-white/10 bg-white/[0.06] text-slate-300',
    ][$tone] ?? 'border-white/10 bg-white/[0.06] text-slate-300';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] {$classes}"]) }}>
    {{ $slot }}
</span>
