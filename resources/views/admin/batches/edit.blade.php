<x-dashboard.shell title="Edit ownership batch" eyebrow="Batch Controls">
    <section class="cca-card overflow-hidden">
        <div class="border-b border-white/10 px-5 py-5 sm:px-7">
            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d4af62]">{{ $batch->batch_code }}</p>
            <h2 class="mt-2 text-2xl font-black text-white">{{ $batch->title }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.batches.update', $batch) }}" class="p-5 sm:p-7">
            @method('PUT')
            @include('admin.batches._form')
        </form>
    </section>
</x-dashboard.shell>
