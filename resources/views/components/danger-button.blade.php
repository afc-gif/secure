<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-lg border border-rose-300/20 bg-rose-300/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.12em] text-rose-100 transition hover:bg-rose-300/15 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2 focus:ring-offset-[#08090b]']) }}>
    {{ $slot }}
</button>
