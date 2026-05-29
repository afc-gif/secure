@props(['step'])

@php
    $steps = [
        1 => ['Identity', 'Legal profile'],
        2 => ['Address', 'Registry'],
        3 => ['Cooperative', 'Interest profile'],
        4 => ['Review', 'Synchronization'],
    ];
@endphp

<div class="grid gap-3 sm:grid-cols-4">
    @foreach ($steps as $number => [$label, $detail])
        <div @class([
            'rounded-lg border p-4 transition duration-300',
            'border-emerald-300/30 bg-emerald-300/10 shadow-glow' => $number === $step,
            'border-[#d4af62]/30 bg-[#d4af62]/10' => $number < $step,
            'border-white/10 bg-white/[0.04]' => $number > $step,
        ])>
            <div class="flex items-center gap-3">
                <span @class([
                    'flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm font-black',
                    'bg-emerald-300 text-[#08100c]' => $number === $step,
                    'bg-[#d4af62] text-[#08100c]' => $number < $step,
                    'bg-white/10 text-slate-400' => $number > $step,
                ])>{{ $number }}</span>
                <div class="min-w-0">
                    <p class="truncate text-sm font-black text-white">{{ $label }}</p>
                    <p class="mt-1 truncate text-xs text-slate-500">{{ $detail }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
