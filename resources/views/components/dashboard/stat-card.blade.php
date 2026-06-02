@props(['label', 'value', 'detail' => null, 'tone' => 'emerald'])

@php
    $tones = [
        'emerald' => 'text-[#d8bf7a]',
        'gold' => 'text-[#ead391]',
        'slate' => 'text-slate-100',
    ];
@endphp

<div class="cca-card p-5">
    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">{{ $label }}</p>
    <p class="mt-4 text-3xl font-black {{ $tones[$tone] ?? $tones['emerald'] }}">{{ $value }}</p>
    @if ($detail)
        <p class="mt-2 text-sm text-slate-400">{{ $detail }}</p>
    @endif
</div>
