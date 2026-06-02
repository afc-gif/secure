<x-dashboard.shell title="Entertainment Asset Console" eyebrow="Administrative Command">
    @php
        $console = $adminConsole;
    @endphp

    <div class="space-y-6">
        <section class="overflow-hidden rounded-lg border border-white/10 bg-black shadow-2xl shadow-black/40">
            <div class="grid gap-0 xl:grid-cols-[1.15fr_0.85fr]">
                <div class="p-6 sm:p-8 lg:p-10">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#8ea2ff]">System Management</p>
                            <h2 class="mt-3 text-4xl font-black leading-tight text-white sm:text-5xl">Secured catalog portfolio</h2>
                        </div>
                        <button type="button" class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-[#243fa4] shadow-[0_0_32px_rgba(36,63,164,0.45)] transition hover:bg-[#2f4fc5]" aria-label="Run asset synchronization">
                            <span class="ml-1 h-0 w-0 border-y-[12px] border-l-[18px] border-y-transparent border-l-white"></span>
                        </button>
                    </div>

                    <div class="mt-8 grid gap-4 md:grid-cols-3">
                        @foreach ($console['cards'] as $card)
                            <div class="rounded-lg border border-white/10 bg-white/[0.045] p-5">
                                <p class="text-xs font-black uppercase leading-5 tracking-[0.18em] text-slate-400">{{ $card['label'] }}</p>
                                <p class="mt-5 font-mono text-3xl font-black tracking-tight text-white">{{ $card['value'] }}</p>
                                <p class="mt-4 text-sm leading-6 text-slate-500">{{ $card['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-white/10 bg-white/[0.035] p-6 sm:p-8 lg:p-10 xl:border-l xl:border-t-0">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#8ea2ff]">Active Asset Class</p>
                    <div class="mt-6 space-y-4">
                        @foreach ($console['assetClasses'] as $index => $assetClass)
                            <button type="button" @class([
                                'w-full rounded-lg border p-5 text-left transition',
                                'border-[#8ea2ff]/45 bg-[#243fa4]/20 shadow-[0_0_28px_rgba(36,63,164,0.22)]' => $assetClass['active'],
                                'border-white/10 bg-black/35 hover:border-white/20 hover:bg-white/[0.055]' => ! $assetClass['active'],
                            ])>
                                <div class="flex gap-4">
                                    <span @class([
                                        'flex h-9 w-9 shrink-0 items-center justify-center rounded-full font-mono text-sm font-black',
                                        'bg-[#8ea2ff] text-black' => $assetClass['active'],
                                        'bg-white/[0.07] text-slate-400' => ! $assetClass['active'],
                                    ])>{{ $index + 1 }}</span>
                                    <span>
                                        <span class="block text-lg font-black text-white">{{ $assetClass['label'] }}</span>
                                        <span class="mt-2 block text-sm leading-6 text-slate-400">{{ $assetClass['description'] }}</span>
                                    </span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
            <div class="rounded-lg border border-white/10 bg-black p-6 shadow-2xl shadow-black/30 sm:p-8">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#8ea2ff]">Cycle Window</p>
                <h3 class="mt-2 text-2xl font-black text-white">Batch 3 synchronization</h3>

                <dl class="mt-7 space-y-4">
                    <div class="rounded-lg border border-white/10 bg-white/[0.045] p-5">
                        <dt class="font-mono text-sm uppercase tracking-[0.16em] text-slate-500">CYCLE_START</dt>
                        <dd class="mt-3 font-mono text-3xl font-black text-white">{{ $console['cycle']['start'] }}</dd>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/[0.045] p-5">
                        <dt class="font-mono text-sm uppercase tracking-[0.16em] text-slate-500">CYCLE_MATURITY</dt>
                        <dd class="mt-3 font-mono text-3xl font-black text-white">{{ $console['cycle']['maturity'] }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-white/10 bg-black p-6 shadow-2xl shadow-black/30 sm:p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#8ea2ff]">Systemic Anchor Nodes</p>
                        <h3 class="mt-2 text-2xl font-black text-white">Launch and clearance sequence</h3>
                    </div>
                    <span class="rounded-full border border-[#8ea2ff]/35 bg-[#243fa4]/20 px-3 py-1 font-mono text-xs font-bold text-[#c4ceff]">ONLINE</span>
                </div>

                <div class="mt-8 space-y-4">
                    @foreach ($console['nodes'] as $index => $node)
                        <div class="grid gap-4 rounded-lg border border-white/10 bg-white/[0.045] p-5 sm:grid-cols-[12rem_1fr] sm:items-start">
                            <div>
                                <p class="font-mono text-sm font-black text-slate-500">NODE_{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}_STATUS</p>
                                <p @class([
                                    'mt-2 inline-flex rounded-full px-3 py-1 font-mono text-xs font-black',
                                    'bg-emerald-300/15 text-emerald-200' => $index === 0,
                                    'bg-[#8ea2ff]/15 text-[#c4ceff]' => $index !== 0,
                                ])>{{ $node['status'] }}</p>
                            </div>
                            <div class="flex gap-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gradient-to-b from-[#dff3ff] to-[#416f9b] text-lg font-black text-white">-&gt;</span>
                                <p class="font-mono text-lg leading-7 text-slate-300">{{ $node['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-lg border border-white/10 bg-black p-6 shadow-2xl shadow-black/30 sm:p-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#8ea2ff]">Stakeholder Activity</p>
                        <h3 class="mt-2 text-2xl font-black text-white">Latest onboarded members</h3>
                    </div>
                    <a href="{{ route('admin.partners.index') }}" class="cca-muted-button">Open Registry</a>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                        <thead class="text-xs uppercase tracking-[0.18em] text-slate-500">
                            <tr>
                                <th class="py-3 pr-5">Partner</th>
                                <th class="px-5 py-3">Token</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="pl-5 py-3">Completed</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 text-slate-300">
                            @forelse ($latestOnboarded as $profile)
                                <tr>
                                    <td class="py-4 pr-5 font-semibold text-white">{{ $profile->full_legal_name }}</td>
                                    <td class="px-5 py-4 font-mono text-xs text-[#8ea2ff]">{{ $profile->user->reference_token }}</td>
                                    <td class="px-5 py-4"><x-profile.status-badge>Onboarded</x-profile.status-badge></td>
                                    <td class="pl-5 py-4">{{ $profile->onboarding_completed_at?->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-5 text-slate-500" colspan="4">No completed onboarding records yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-lg border border-white/10 bg-black p-6 shadow-2xl shadow-black/30 sm:p-8">
                <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#8ea2ff]">Administrative Clearance</p>
                <h3 class="mt-2 text-2xl font-black text-white">Token and cycle controls</h3>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-lg border border-white/10 bg-white/[0.045] p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Active Tokens</p>
                        <p class="mt-4 font-mono text-3xl font-black text-white">{{ $activeTokens }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/[0.045] p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Used Tokens</p>
                        <p class="mt-4 font-mono text-3xl font-black text-white">{{ $usedTokens }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/[0.045] p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Active Members</p>
                        <p class="mt-4 font-mono text-3xl font-black text-white">{{ $activeMembers }}</p>
                    </div>
                    <div class="rounded-lg border border-white/10 bg-white/[0.045] p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Participants</p>
                        <p class="mt-4 font-mono text-3xl font-black text-white">{{ $totalParticipants }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-dashboard.shell>
