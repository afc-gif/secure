@props(['items', 'labelKey' => 'label', 'valueKey' => 'total', 'currency' => 'USD'])

@php($max = max(1, (float) $items->max($valueKey)))

<div class="space-y-4">
    @forelse ($items as $item)
        @php($value = (float) data_get($item, $valueKey, 0))
        <div>
            <div class="flex items-center justify-between gap-4 text-sm">
                <span class="font-semibold text-slate-300">{{ data_get($item, $labelKey) }}</span>
                <span class="font-black text-[#d4af62]">{{ $currency }} {{ number_format($value, 2) }}</span>
            </div>
            <div class="mt-2 h-2 rounded-full bg-white/10">
                <div class="h-2 rounded-full bg-gradient-to-r from-emerald-400 to-[#d4af62]" style="width: {{ max(4, min(100, ($value / $max) * 100)) }}%"></div>
            </div>
        </div>
    @empty
        <p class="text-sm text-slate-500">No analytics data yet.</p>
    @endforelse
</div>
