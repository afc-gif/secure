<x-dashboard.shell title="Batch cycle management" eyebrow="Ownership Cycles">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
    @endif

    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="max-w-2xl text-sm leading-6 text-slate-400">Create, activate, lock, and archive countryside ownership cycles.</p>
        <a href="{{ route('admin.batches.create') }}" class="cca-button">Create Batch</a>
    </div>

    <div class="grid gap-5 xl:grid-cols-2">
        @forelse ($batches as $batch)
            <x-ownership.card :batch="$batch">
                <x-slot:action>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.batches.edit', $batch) }}" class="cca-muted-button">Edit Batch</a>
                        <a href="{{ route('admin.tokens.create', ['batch_id' => $batch->id]) }}" class="cca-muted-button">Generate Tokens</a>
                        @if ($batch->status !== 'archived')
                            <form method="POST" action="{{ route('admin.batches.archive', $batch) }}">
                                @csrf
                                @method('PATCH')
                                <button class="cca-muted-button">Archive</button>
                            </form>
                        @endif
                    </div>
                </x-slot:action>
            </x-ownership.card>
        @empty
            <section class="cca-card p-6 text-sm text-slate-500">No ownership batches have been created yet.</section>
        @endforelse
    </div>

    <div class="mt-6">{{ $batches->links() }}</div>
</x-dashboard.shell>
