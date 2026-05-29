<x-dashboard.shell title="Create ownership batch" eyebrow="Batch Activation">
    <section class="cca-card overflow-hidden">
        <div class="border-b border-white/10 px-5 py-5 sm:px-7">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d4af62]">New Cooperative Cycle</p>
            <h2 class="mt-2 text-2xl font-black text-white">Configure batch access window</h2>
        </div>
        <form method="POST" action="{{ route('admin.batches.store') }}" class="p-5 sm:p-7">
            @include('admin.batches._form')
        </form>
    </section>
</x-dashboard.shell>
