<x-dashboard.shell title="Member Portfolio" eyebrow="Verified Member Dashboard">
    @php
        $panel = $individualPanel;
        $milestones = $panel['milestones'];
    @endphp

    <div class="space-y-5">
        <section class="overflow-hidden rounded-lg border border-[#d8bf7a]/15 bg-[#0d0f13]">
            <div class="grid lg:grid-cols-[1.15fr_0.85fr]">
                <div class="p-5 sm:p-7 lg:p-8">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full border border-[#d8bf7a]/25 bg-[#d8bf7a]/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.16em] text-[#ead391]">
                            <span class="h-2 w-2 rounded-full bg-[#d8bf7a]"></span>
                            Verified
                        </span>
                        <span class="rounded-full border border-white/10 bg-white/[0.04] px-3 py-1.5 font-mono text-xs text-slate-400">{{ $panel['gate']['variable'] }}</span>
                    </div>

                    <div class="mt-8 max-w-3xl">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Secured Benefit Balance</p>
                        <h2 class="mt-3 font-mono text-4xl font-black tracking-tight text-white sm:text-5xl">USD 33,000.00</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-400">Legally verified carried contract allocation synchronized to the active Batch 3 member cycle.</p>
                    </div>

                    <div class="mt-7 grid gap-3 sm:grid-cols-2">
                        <div class="border-t border-[#d8bf7a]/20 pt-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Member Credential</p>
                            <p class="mt-2 font-mono text-lg font-bold text-white">{{ $panel['gate']['access_input'] }}</p>
                        </div>
                        <div class="border-t border-[#d8bf7a]/20 pt-4">
                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Status</p>
                            <p class="mt-2 text-sm font-bold uppercase leading-6 text-[#ead391]">Legally Verified / Carried Contract Allocation</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-[#d8bf7a]/10 bg-white/[0.025] p-5 sm:p-7 lg:border-l lg:border-t-0 lg:p-8">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d8bf7a]">Allocation Split</p>
                    <div class="mt-5 divide-y divide-white/10">
                        @foreach ($panel['dataBlocks'] as $block)
                            <div class="py-4 first:pt-0 last:pb-0">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-bold text-white">{{ $block['header'] }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-500">{{ $block['label'] }}</p>
                                    </div>
                                    <p class="shrink-0 font-mono text-base font-black text-[#ead391]">{{ $block['allocation'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-5 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-lg border border-[#d8bf7a]/15 bg-[#0d0f13] p-5 sm:p-7">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d8bf7a]">Disbursement</p>
                        <h3 class="mt-2 text-xl font-extrabold text-white">Settlement Coordinates</h3>
                    </div>
                    <span class="rounded-full border border-[#d8bf7a]/20 bg-[#d8bf7a]/10 px-3 py-1 text-xs font-bold text-[#ead391]">Synced</span>
                </div>

                <dl class="mt-6 divide-y divide-white/10">
                    <div class="grid gap-1 py-4 first:pt-0 sm:grid-cols-[9rem_1fr] sm:gap-4">
                        <dt class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Recipient</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['recipient'] }}</dd>
                    </div>
                    <div class="grid gap-1 py-4 sm:grid-cols-[9rem_1fr] sm:gap-4">
                        <dt class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Address</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['address'] }}</dd>
                    </div>
                    <div class="grid gap-1 py-4 last:pb-0 sm:grid-cols-[9rem_1fr] sm:gap-4">
                        <dt class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Destination</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['destination'] }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-[#d8bf7a]/15 bg-[#0d0f13] p-5 sm:p-7">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d8bf7a]">Timeline</p>
                        <h3 class="mt-2 text-xl font-extrabold text-white">Batch 3 Cycle</h3>
                    </div>
                    <span class="rounded-full border border-white/10 bg-white/[0.04] px-3 py-1 font-mono text-xs font-bold text-slate-300">Active</span>
                </div>

                <div class="mt-7 grid gap-3">
                    @foreach ($milestones as $index => $milestone)
                        <div class="grid grid-cols-[2.5rem_1fr] gap-4 rounded-lg border border-white/10 bg-white/[0.025] p-4">
                            <div @class([
                                'flex h-10 w-10 items-center justify-center rounded-full border text-sm font-black',
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

        <section class="rounded-lg border border-[#d8bf7a]/15 bg-[#0d0f13] p-5 sm:p-7">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d8bf7a]">Records</p>
                    <h3 class="mt-2 text-xl font-extrabold text-white">Contract Footprint</h3>
                </div>
                <span class="rounded-full border border-white/10 bg-white/[0.04] px-3 py-1 text-xs font-bold uppercase tracking-[0.14em] text-slate-300">Read Only</span>
            </div>

            <div class="mt-6 grid gap-3 lg:grid-cols-2">
                <div class="rounded-lg border border-white/10 bg-white/[0.025] p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Agreement</p>
                    <p class="mt-2 font-mono text-sm leading-6 text-slate-200">{{ $panel['documents']['line_1'] }}</p>
                </div>
                <div class="rounded-lg border border-white/10 bg-white/[0.025] p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Registration</p>
                    <p class="mt-2 font-mono text-sm leading-6 text-slate-200">{{ $panel['documents']['line_2'] }}</p>
                </div>
            </div>

            <div class="mt-6 divide-y divide-white/10">
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
