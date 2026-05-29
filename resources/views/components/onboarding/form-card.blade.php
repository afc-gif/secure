@props(['title', 'eyebrow', 'description' => null])

<section {{ $attributes->merge(['class' => 'cca-card overflow-hidden']) }}>
    <div class="border-b border-white/10 px-5 py-5 sm:px-7">
        <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d4af62]">{{ $eyebrow }}</p>
        <h2 class="mt-2 text-2xl font-black text-white">{{ $title }}</h2>
        @if ($description)
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">{{ $description }}</p>
        @endif
    </div>
    <div class="p-5 sm:p-7">
        {{ $slot }}
    </div>
</section>
