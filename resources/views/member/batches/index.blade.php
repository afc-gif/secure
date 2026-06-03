<x-dashboard.shell title="Active ownership cycles" eyebrow="Cooperative Batches">
    <div class="mb-5 rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="cca-kicker">Cycle Registry</p>
                <h2 class="mt-2 text-xl font-black text-white">Open cooperative participation windows</h2>
            </div>
            <a href="{{ route('member.access-token.create') }}" class="cca-muted-button">Validate Token</a>
        </div>
    </div>

    <div class="grid gap-5 xl:grid-cols-[1.15fr_0.85fr]">
        <section class="space-y-5">
            @forelse ($activeBatches as $batch)
                <x-ownership.card :batch="$batch">
                    <x-slot:action>
                        <a href="{{ route('member.access-token.create') }}" class="cca-button">Unlock Ownership Access</a>
                    </x-slot:action>
                </x-ownership.card>
            @empty
                <section class="cca-card p-6">
                    <p class="cca-kicker">Cycle Access</p>
                    <h2 class="mt-3 text-2xl font-black text-white">No active ownership cycles</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-400">CCA has not opened a cooperative participation window at this time.</p>
                </section>
            @endforelse
        </section>

        <x-ownership.table :participations="$participations" />
    </div>
</x-dashboard.shell>
