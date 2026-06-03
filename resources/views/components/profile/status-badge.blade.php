@props(['tone' => 'emerald'])

@php
    $classes = [
        'emerald' => 'border-[#f35aa5]/25 bg-[#f35aa5]/10 text-[#ffd4e9]',
        'gold' => 'border-[#f35f8d]/25 bg-[#f35f8d]/10 text-[#ffd0bf]',
        'slate' => 'border-white/10 bg-white/[0.06] text-slate-300',
    ][$tone] ?? 'border-white/10 bg-white/[0.06] text-slate-300';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] {$classes}"]) }}>
    {{ $slot }}
</span>
