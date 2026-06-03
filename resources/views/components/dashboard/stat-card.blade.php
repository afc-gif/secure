@props(['label', 'value', 'detail' => null, 'tone' => 'emerald'])

@php
    $tones = [
        'emerald' => 'text-[#d8bf7a]',
        'gold' => 'text-[#ead391]',
        'slate' => 'text-slate-100',
    ];
@endphp

<div class="cca-card min-w-0 p-4 sm:p-5">
    <p class="text-xs font-bold uppercase leading-5 tracking-[0.14em] text-slate-500">{{ $label }}</p>
    <p class="mt-3 break-words text-2xl font-black leading-tight sm:mt-4 sm:text-3xl {{ $tones[$tone] ?? $tones['emerald'] }}">{{ $value }}</p>
    @if ($detail)
        <p class="mt-2 text-sm leading-6 text-slate-400">{{ $detail }}</p>
    @endif
</div>
