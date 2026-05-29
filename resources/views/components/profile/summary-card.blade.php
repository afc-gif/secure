@props(['label', 'value', 'detail' => null, 'tone' => 'white'])

@php
    $valueClass = [
        'emerald' => 'text-emerald-300',
        'gold' => 'text-[#d4af62]',
        'white' => 'text-white',
    ][$tone] ?? 'text-white';
@endphp

<section {{ $attributes->merge(['class' => 'cca-card p-5']) }}>
    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">{{ $label }}</p>
    <h3 class="mt-4 text-2xl font-black {{ $valueClass }}">{{ $value }}</h3>
    @if ($detail)
        <p class="mt-3 text-sm leading-6 text-slate-400">{{ $detail }}</p>
    @endif
</section>
