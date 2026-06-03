<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-[#7c3cff] via-[#d936ad] to-[#f35f8d] px-4 py-2 text-xs font-bold uppercase tracking-[0.12em] text-white shadow-lg shadow-black/25 transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-[#f35aa5] focus:ring-offset-2 focus:ring-offset-[#08090b]']) }}>
    {{ $slot }}
</button>
