<x-dashboard.shell title="Edit active cycle" eyebrow="Cycle Controls">
    <section class="cca-card overflow-hidden">
        <div class="border-b border-white/10 px-4 py-4 sm:px-7 sm:py-5">
            <p class="break-all text-xs font-bold uppercase tracking-[0.14em] text-slate-500">{{ $batch->batch_code }}</p>
            <h2 class="mt-2 break-words text-xl font-black text-white sm:text-2xl">{{ $batch->title }}</h2>
        </div>
        <form method="POST" action="{{ route('admin.batches.update', $batch) }}" class="p-4 sm:p-7">
            @method('PUT')
            @include('admin.batches._form')
        </form>
    </section>
</x-dashboard.shell>
