@props(['title', 'description'])

<div class="rounded-lg border border-[#d4af62]/20 bg-[#d4af62]/10 p-4 sm:p-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-[#d4af62]/30 bg-black/25 text-[#d4af62]">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M7 10V8a5 5 0 0 1 10 0v2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                <path d="M6 10h12v9H6z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
            </svg>
        </div>
        <div>
            <h3 class="text-sm font-black uppercase tracking-[0.18em] text-white">{{ $title }}</h3>
            <p class="mt-2 text-sm leading-6 text-slate-400">{{ $description }}</p>
        </div>
    </div>
</div>
