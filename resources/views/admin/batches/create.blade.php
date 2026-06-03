<x-dashboard.shell title="Create ownership batch" eyebrow="Batch Activation">
    <section class="cca-card overflow-hidden">
        <div class="border-b border-white/10 px-4 py-4 sm:px-7 sm:py-5">
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">New Cooperative Cycle</p>
            <h2 class="mt-2 text-xl font-black text-white sm:text-2xl">Configure batch access window</h2>
        </div>
        <form method="POST" action="{{ route('admin.batches.store') }}" class="p-4 sm:p-7">
            @include('admin.batches._form')
        </form>
    </section>
</x-dashboard.shell>
