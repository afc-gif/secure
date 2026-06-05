<x-dashboard.shell title="Active ownership cycles" eyebrow="Cooperative Batches">
    <div class="mb-5 rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="cca-kicker">Cycle Registry</p>
                <h2 class="mt-2 text-xl font-black text-white">Secured Revenue Streams</h2>
            </div>
            <span class="rounded-md border border-[#f35aa5]/20 bg-[#f35aa5]/10 px-3 py-2 text-xs font-black uppercase tracking-[0.12em] text-[#ffd4e9]">Access Active</span>
        </div>
    </div>

    <div class="grid gap-5 xl:grid-cols-[1.15fr_0.85fr]">
        <section class="space-y-5">
            @forelse ($activeBatches as $batch)
                <x-ownership.card :batch="$batch">
                    <x-slot:action>
                        <span class="cca-button">{{ $batch->batch_code === 'SECURE-GROUNDS-03' ? 'SECURED POSITION PENDING' : 'SECURED POSITION ACTIVE' }}</span>
                    </x-slot:action>
                </x-ownership.card>
            @empty
                <section class="cca-card p-6">
                    <p class="cca-kicker">Cycle Access</p>
                    <h2 class="mt-3 text-2xl font-black text-white">No active ownership cycles</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-400">No secured revenue streams are available at this time.</p>
                </section>
            @endforelse
        </section>

        <x-ownership.table :participations="$participations" />
    </div>
</x-dashboard.shell>
