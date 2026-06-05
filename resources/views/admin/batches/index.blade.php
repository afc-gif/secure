<x-dashboard.shell title="Active cycle management" eyebrow="Ownership Cycles">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
    @endif

    <div class="mb-4 flex flex-col gap-3 sm:mb-6 sm:flex-row sm:items-center sm:justify-between">
        <p class="max-w-2xl text-sm leading-6 text-slate-400">Create, activate, edit, delete, and publish secured revenue streams from database-backed active cycles.</p>
        <a href="{{ route('admin.batches.create') }}" class="cca-button w-full sm:w-auto">Create Active Cycle</a>
    </div>

    <div class="grid gap-4 sm:gap-5 xl:grid-cols-2">
        @forelse ($batches as $batch)
            <x-ownership.card :batch="$batch">
                <x-slot:action>
                    <div class="grid gap-3 sm:flex sm:flex-wrap">
                        <a href="{{ route('admin.batches.edit', $batch) }}" class="cca-muted-button w-full sm:w-auto">Edit Active Cycle</a>
                        <a href="{{ route('admin.tokens.create', ['batch_id' => $batch->id]) }}" class="cca-muted-button w-full sm:w-auto">Generate Secure Tokens</a>
                        @if ($batch->status !== 'archived')
                            <form method="POST" action="{{ route('admin.batches.archive', $batch) }}">
                                @csrf
                                @method('PATCH')
                                <button class="cca-muted-button w-full sm:w-auto">Archive</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.batches.destroy', $batch) }}" onsubmit="return confirm('Delete this active cycle from the database? Related access tokens and participation records will also be removed.');">
                            @csrf
                            @method('DELETE')
                            <button class="cca-muted-button w-full sm:w-auto">Delete</button>
                        </form>
                    </div>
                </x-slot:action>
            </x-ownership.card>
        @empty
            <section class="cca-card p-6 text-sm text-slate-500">No active cycles have been created yet.</section>
        @endforelse
    </div>

    <div class="mt-6">{{ $batches->links() }}</div>
</x-dashboard.shell>
