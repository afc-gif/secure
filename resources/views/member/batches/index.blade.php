<x-dashboard.shell title="Active ownership cycles" eyebrow="Cooperative Batches">
    <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
        <section class="space-y-5">
            @forelse ($activeBatches as $batch)
                <x-ownership.card :batch="$batch">
                    <x-slot:action>
                        <a href="{{ route('member.access-token.create') }}" class="cca-button">Unlock Ownership Access</a>
                    </x-slot:action>
                </x-ownership.card>
            @empty
                <section class="cca-card p-6">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Cycle Access</p>
                    <h2 class="mt-3 text-2xl font-black text-white">No active ownership cycles</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-400">CCA has not opened a cooperative participation window at this time.</p>
                </section>
            @endforelse
        </section>

        <x-ownership.table :participations="$participations" />
    </div>
</x-dashboard.shell>
