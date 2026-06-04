<section {{ $attributes->merge(['class' => 'cca-card overflow-hidden']) }}>
    <div class="relative overflow-hidden border-b border-white/[0.07] px-4 py-5 sm:px-7">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_10%,rgba(217,54,173,0.16),transparent_18rem)]"></div>
        <div class="relative">
        <p class="cca-kicker">Secure Access Token</p>
        <h2 class="mt-2 text-xl font-black text-white sm:text-2xl">Unlock Dashboard Privilege</h2>
        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">Enter a validated secure access token to activate dashboard privilege for the synchronized member cycle.</p>
        </div>
    </div>
    <div class="p-4 sm:p-7">
        {{ $slot }}
    </div>
</section>
