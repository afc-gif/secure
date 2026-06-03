<x-dashboard.shell title="Entertainment Asset Console" eyebrow="Administrative Command">
    @php
        $console = $adminConsole;
    @endphp

    <div class="space-y-4 sm:space-y-6">
        <section class="overflow-hidden rounded-lg border border-white/[0.07] bg-[#0b0d10] shadow-xl shadow-black/25">
            <div class="grid gap-0 xl:grid-cols-[1.15fr_0.85fr]">
                <div class="min-w-0 p-4 sm:p-8 lg:p-10">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">System Management</p>
                            <h2 class="mt-3 text-3xl font-black leading-tight text-white sm:text-5xl">Secured catalog portfolio</h2>
                        </div>
                        <button type="button" class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md border border-white/10 bg-white/[0.06] transition hover:bg-white/[0.09] sm:h-14 sm:w-14" aria-label="Run asset synchronization">
                            <span class="ml-0.5 h-0 w-0 border-y-[8px] border-l-[12px] border-y-transparent border-l-white sm:border-y-[10px] sm:border-l-[15px]"></span>
                        </button>
                    </div>

                    <div class="mt-6 grid gap-3 sm:mt-8 md:grid-cols-3">
                        @foreach ($console['cards'] as $card)
                            <div class="min-w-0 rounded-lg border border-white/[0.07] bg-white/[0.035] p-4 sm:p-5">
                                <p class="text-xs font-black uppercase leading-5 tracking-[0.14em] text-slate-500">{{ $card['label'] }}</p>
                                <p class="mt-4 break-words font-mono text-2xl font-black leading-tight tracking-tight text-white sm:text-3xl">{{ $card['value'] }}</p>
                                <p class="mt-3 text-sm leading-6 text-slate-500 sm:mt-4">{{ $card['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="border-t border-white/[0.07] bg-white/[0.025] p-4 sm:p-8 lg:p-10 xl:border-l xl:border-t-0">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Active Asset Class</p>
                    <div class="mt-4 space-y-3 sm:mt-6 sm:space-y-4">
                        @foreach ($console['assetClasses'] as $index => $assetClass)
                            <button type="button" @class([
                                'w-full rounded-lg border p-4 text-left transition sm:p-5',
                                'border-white/15 bg-white/[0.06]' => $assetClass['active'],
                                'border-white/[0.07] bg-black/20 hover:border-white/15 hover:bg-white/[0.045]' => ! $assetClass['active'],
                            ])>
                                <div class="flex min-w-0 gap-3 sm:gap-4">
                                    <span @class([
                                        'flex h-8 w-8 shrink-0 items-center justify-center rounded-md font-mono text-sm font-black sm:h-9 sm:w-9',
                                        'bg-white/12 text-white' => $assetClass['active'],
                                        'bg-white/[0.07] text-slate-400' => ! $assetClass['active'],
                                    ])>{{ $index + 1 }}</span>
                                    <span class="min-w-0">
                                        <span class="block break-words text-base font-black text-white sm:text-lg">{{ $assetClass['label'] }}</span>
                                        <span class="mt-2 block text-sm leading-6 text-slate-400">{{ $assetClass['description'] }}</span>
                                    </span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:gap-6 xl:grid-cols-[0.8fr_1.2fr]">
            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/20 sm:p-8">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Cycle Window</p>
                <h3 class="mt-2 text-xl font-black text-white sm:text-2xl">Batch 3 synchronization</h3>

                <dl class="mt-5 space-y-3 sm:mt-7 sm:space-y-4">
                    <div class="min-w-0 rounded-lg border border-white/[0.07] bg-white/[0.035] p-4 sm:p-5">
                        <dt class="font-mono text-xs uppercase tracking-[0.12em] text-slate-500 sm:text-sm">CYCLE_START</dt>
                        <dd class="mt-3 break-words font-mono text-2xl font-black leading-tight text-white sm:text-3xl">{{ $console['cycle']['start'] }}</dd>
                    </div>
                    <div class="min-w-0 rounded-lg border border-white/[0.07] bg-white/[0.035] p-4 sm:p-5">
                        <dt class="font-mono text-xs uppercase tracking-[0.12em] text-slate-500 sm:text-sm">CYCLE_MATURITY</dt>
                        <dd class="mt-3 break-words font-mono text-2xl font-black leading-tight text-white sm:text-3xl">{{ $console['cycle']['maturity'] }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/20 sm:p-8">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Systemic Anchor Nodes</p>
                        <h3 class="mt-2 text-xl font-black text-white sm:text-2xl">Launch and clearance sequence</h3>
                    </div>
                    <span class="w-max rounded-md border border-white/[0.08] bg-white/[0.03] px-3 py-1 font-mono text-xs font-bold text-slate-300">ONLINE</span>
                </div>

                <div class="mt-5 space-y-3 sm:mt-8 sm:space-y-4">
                    @foreach ($console['nodes'] as $index => $node)
                        <div class="grid min-w-0 gap-4 rounded-lg border border-white/[0.07] bg-white/[0.035] p-4 sm:grid-cols-[12rem_1fr] sm:items-start sm:p-5">
                            <div class="min-w-0">
                                <p class="break-all font-mono text-xs font-black text-slate-500 sm:text-sm">NODE_{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}_STATUS</p>
                                <p @class([
                                    'mt-2 inline-flex rounded-md px-3 py-1 font-mono text-xs font-black',
                                    'bg-emerald-300/15 text-emerald-200' => $index === 0,
                                    'bg-white/[0.06] text-slate-300' => $index !== 0,
                                ])>{{ $node['status'] }}</p>
                            </div>
                            <div class="flex min-w-0 gap-3">
                                <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-white/[0.08] bg-white/[0.05] text-sm font-black text-slate-300">-&gt;</span>
                                <p class="min-w-0 break-words font-mono text-sm leading-6 text-slate-300 sm:text-lg sm:leading-7">{{ $node['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/20 sm:p-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Stakeholder Activity</p>
                        <h3 class="mt-2 text-xl font-black text-white sm:text-2xl">Latest onboarded members</h3>
                    </div>
                    <a href="{{ route('admin.partners.index') }}" class="cca-muted-button">Open Registry</a>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-[46rem] divide-y divide-white/10 text-left text-sm">
                        <thead class="text-xs uppercase tracking-[0.12em] text-slate-500">
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
                                    <td class="max-w-[14rem] py-4 pr-5 font-semibold text-white">{{ $profile->full_legal_name }}</td>
                                    <td class="px-5 py-4 font-mono text-xs text-slate-300">{{ $profile->user->reference_token }}</td>
                                    <td class="px-5 py-4"><x-profile.status-badge>Onboarded</x-profile.status-badge></td>
                                    <td class="whitespace-nowrap py-4 pl-5">{{ $profile->onboarding_completed_at?->diffForHumans() }}</td>
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

            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/20 sm:p-8">
                <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Administrative Clearance</p>
                <h3 class="mt-2 text-xl font-black text-white sm:text-2xl">Token and cycle controls</h3>

                <div class="mt-5 grid gap-3 sm:mt-6 sm:grid-cols-2 sm:gap-4">
                    <div class="min-w-0 rounded-lg border border-white/[0.07] bg-white/[0.035] p-4 sm:p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Active Tokens</p>
                        <p class="mt-3 break-words font-mono text-2xl font-black text-white sm:mt-4 sm:text-3xl">{{ $activeTokens }}</p>
                    </div>
                    <div class="min-w-0 rounded-lg border border-white/[0.07] bg-white/[0.035] p-4 sm:p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Used Tokens</p>
                        <p class="mt-3 break-words font-mono text-2xl font-black text-white sm:mt-4 sm:text-3xl">{{ $usedTokens }}</p>
                    </div>
                    <div class="min-w-0 rounded-lg border border-white/[0.07] bg-white/[0.035] p-4 sm:p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Active Members</p>
                        <p class="mt-3 break-words font-mono text-2xl font-black text-white sm:mt-4 sm:text-3xl">{{ $activeMembers }}</p>
                    </div>
                    <div class="min-w-0 rounded-lg border border-white/[0.07] bg-white/[0.035] p-4 sm:p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Participants</p>
                        <p class="mt-3 break-words font-mono text-2xl font-black text-white sm:mt-4 sm:text-3xl">{{ $totalParticipants }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-dashboard.shell>
