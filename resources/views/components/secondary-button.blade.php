<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-lg border border-white/[0.08] bg-white/[0.04] px-4 py-2 text-xs font-bold uppercase tracking-[0.12em] text-slate-200 shadow-sm hover:bg-white/[0.07] focus:outline-none focus:ring-2 focus:ring-[#f35aa5] focus:ring-offset-2 focus:ring-offset-[#08090b] disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
