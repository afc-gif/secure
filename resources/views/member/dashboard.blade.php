<x-dashboard.shell title="Member Portfolio" eyebrow="Verified Member Dashboard">
    @php
        $panel = $individualPanel;
        $milestones = $panel['milestones'];
    @endphp

    <div class="space-y-6">
        <section class="cca-private-panel overflow-hidden">
            <div class="grid lg:grid-cols-[1.25fr_0.75fr]">
                <div class="relative p-6 sm:p-8 lg:p-10">
                    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#d8bf7a]/60 to-transparent"></div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="inline-flex items-center gap-2 rounded-md border border-[#d8bf7a]/20 bg-[#d8bf7a]/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.14em] text-[#ead391]">
                            <span class="h-1.5 w-1.5 rounded-full bg-[#d8bf7a]"></span>
                            Verified
                        </span>
                        <span class="rounded-md border border-white/[0.08] bg-white/[0.03] px-3 py-1.5 font-mono text-xs text-slate-400">{{ $panel['gate']['variable'] }}</span>
                    </div>

                    <div class="mt-10 max-w-3xl">
                        <p class="cca-kicker">Secured Benefit Balance</p>
                        <h2 class="mt-4 text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-6xl">USD 33,000.00</h2>
                        <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300">Legally verified carried contract allocation synchronized to the active Batch 3 member cycle.</p>
                    </div>

                    <div class="mt-8 grid gap-3 sm:grid-cols-2">
                        <div class="cca-private-inset p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Member Credential</p>
                            <p class="mt-3 font-mono text-base font-bold text-white">{{ $panel['gate']['access_input'] }}</p>
                        </div>
                        <div class="cca-private-inset p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Status</p>
                            <p class="mt-3 text-sm font-bold uppercase leading-6 text-[#ead391]">Legally Verified / Carried Contract Allocation</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/[0.07] bg-[#0c0e11] p-6 sm:p-8 lg:border-l lg:border-t-0 lg:p-10">
                    <p class="cca-kicker">Allocation Split</p>
                    <div class="mt-6 space-y-3">
                        @foreach ($panel['dataBlocks'] as $block)
                            <div class="cca-private-inset p-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $block['header'] }}</p>
                                        <p class="mt-1.5 text-xs uppercase tracking-[0.14em] text-slate-500">{{ $block['label'] }}</p>
                                    </div>
                                    <p class="shrink-0 text-base font-black text-[#ead391]">{{ $block['allocation'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[0.92fr_1.08fr]">
            <div class="cca-private-panel p-6 sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="cca-kicker">Disbursement</p>
                        <h3 class="mt-2 text-xl font-extrabold text-white">Settlement Coordinates</h3>
                    </div>
                    <span class="rounded-md border border-[#d8bf7a]/20 bg-[#d8bf7a]/10 px-3 py-1 text-xs font-bold text-[#ead391]">Synced</span>
                </div>

                <dl class="mt-7 space-y-3">
                    <div class="cca-private-inset grid gap-1 p-4 sm:grid-cols-[9rem_1fr] sm:gap-4">
                        <dt class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Recipient</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['recipient'] }}</dd>
                    </div>
                    <div class="cca-private-inset grid gap-1 p-4 sm:grid-cols-[9rem_1fr] sm:gap-4">
                        <dt class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Address</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['address'] }}</dd>
                    </div>
                    <div class="cca-private-inset grid gap-1 p-4 sm:grid-cols-[9rem_1fr] sm:gap-4">
                        <dt class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Destination</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['destination'] }}</dd>
                    </div>
                </dl>
            </div>

            <div class="cca-private-panel p-6 sm:p-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="cca-kicker">Timeline</p>
                        <h3 class="mt-2 text-xl font-extrabold text-white">Batch 3 Cycle</h3>
                    </div>
                    <span class="rounded-md border border-white/[0.08] bg-white/[0.03] px-3 py-1 font-mono text-xs font-bold text-slate-300">Active</span>
                </div>

                <div class="mt-7 grid gap-3">
                    @foreach ($milestones as $index => $milestone)
                        <div class="cca-private-inset grid grid-cols-[2.5rem_1fr] gap-4 p-4">
                            <div @class([
                                'flex h-10 w-10 items-center justify-center rounded-md border text-sm font-black',
                                'border-[#d8bf7a] bg-[#d8bf7a] text-[#08090b]' => $index <= 1,
                                'border-white/15 bg-transparent text-slate-500' => $index > 1,
                            ])>{{ $index + 1 }}</div>
                            <div>
                                <p class="text-sm font-bold text-white">{{ $milestone['date'] }}</p>
                                <p class="mt-1 text-sm leading-6 text-slate-400">{{ $milestone['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="cca-private-panel p-6 sm:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="cca-kicker">Records</p>
                    <h3 class="mt-2 text-xl font-extrabold text-white">Contract Footprint</h3>
                </div>
                <span class="rounded-md border border-white/[0.08] bg-white/[0.03] px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-slate-300">Read Only</span>
            </div>

            <div class="mt-7 grid gap-3 lg:grid-cols-2">
                <div class="cca-private-inset p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Agreement</p>
                    <p class="mt-2 font-mono text-sm leading-6 text-slate-200">{{ $panel['documents']['line_1'] }}</p>
                </div>
                <div class="cca-private-inset p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Registration</p>
                    <p class="mt-2 font-mono text-sm leading-6 text-slate-200">{{ $panel['documents']['line_2'] }}</p>
                </div>
            </div>

            <div class="mt-7 divide-y divide-white/[0.07]">
                @foreach ($panel['history'] as $history)
                    <div class="grid gap-2 py-4 first:pt-0 last:pb-0 sm:grid-cols-[9rem_1fr] sm:gap-5">
                        <div>
                            <p class="text-sm font-bold text-white">{{ $history['record'] }}</p>
                            <p class="mt-1 font-mono text-xs text-slate-500">{{ $history['date'] }}</p>
                        </div>
                        <p class="font-mono text-sm leading-6 text-slate-300">{{ $history['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-dashboard.shell>
