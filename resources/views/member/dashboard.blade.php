<x-dashboard.shell title="Member Portfolio" eyebrow="Verified Member Dashboard">
    @php
        $panel = $individualPanel;
        $milestones = $panel['milestones'];
    @endphp

    <div class="grid gap-4 lg:gap-5 xl:grid-cols-[minmax(0,1fr)_19rem]">
        <section class="relative overflow-hidden rounded-lg border border-white/[0.08] bg-[#151018] p-5 shadow-2xl shadow-black/25 sm:p-7">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_12%_15%,rgba(255,255,255,0.16),transparent_18rem),linear-gradient(135deg,#7c3cff_0%,#d936ad_48%,#f35f8d_100%)]"></div>
            <div class="absolute -right-12 bottom-0 h-40 w-40 rounded-full border border-white/15 bg-white/[0.05]"></div>
            <div class="relative">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.13em] text-white/70">Secured Benefit Balance</p>
                    <h2 class="mt-3 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl">USD 33,000.00</h2>
                    <p class="mt-4 max-w-xl text-sm leading-6 text-white/75">Legally verified carried contract allocation synchronized to the active Batch 3 member cycle.</p>
                </div>
                <span class="inline-flex items-center gap-2 rounded-md border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.12em] text-white">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                    Verified
                </span>
            </div>

            <div class="mt-8 h-16 rounded-lg border border-white/10 bg-white/[0.06] px-4 py-3">
                <div class="flex h-full items-end gap-2">
                    <span class="h-4 flex-1 rounded-t bg-white/20"></span>
                    <span class="h-7 flex-1 rounded-t bg-white/30"></span>
                    <span class="h-10 flex-1 rounded-t bg-white/45"></span>
                    <span class="h-8 flex-1 rounded-t bg-white/30"></span>
                    <span class="h-5 flex-1 rounded-t bg-white/20"></span>
                    <span class="h-9 flex-1 rounded-t bg-white/35"></span>
                    <span class="h-12 flex-1 rounded-t bg-white/50"></span>
                    <span class="h-14 flex-1 rounded-t bg-white/60"></span>
                </div>
            </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
            <div class="rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20">
                <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Member Credential</p>
                <p class="mt-3 break-all font-mono text-sm font-black leading-6 text-white">{{ $panel['gate']['access_input'] }}</p>
                <p class="mt-3 max-w-full break-all rounded-md border border-white/[0.06] bg-white/[0.02] px-3 py-2 font-mono text-xs text-slate-500">{{ $panel['gate']['variable'] }}</p>
            </div>

            <div class="rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20">
                <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Status</p>
                <p class="mt-3 text-sm font-black uppercase leading-6 text-white">Legally Verified / Carried Contract Allocation</p>
                <div class="mt-4 rounded-md border border-[#f35aa5]/20 bg-[#f35aa5]/10 px-3 py-2 text-xs font-bold uppercase tracking-[0.12em] text-[#ffd4e9]">Active</div>
            </div>
        </section>

        <section class="rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-6 xl:col-span-1">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-black text-white">Allocation Split</p>
                <span class="text-xs font-semibold text-slate-500">Batch 3 Cycle</span>
            </div>

            <div class="mt-5 grid gap-3 md:grid-cols-3">
                @foreach ($panel['dataBlocks'] as $block)
                    <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10]/80 p-4">
                        <p class="text-sm font-bold text-white">{{ $block['header'] }}</p>
                        <p class="mt-2 text-xs uppercase leading-5 tracking-[0.12em] text-slate-500">{{ $block['label'] }}</p>
                        <p class="mt-5 text-xl font-black leading-tight text-white">{{ $block['allocation'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-6 xl:row-span-2">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-black text-white">Timeline</p>
                    <p class="mt-1 text-xs text-slate-500">Batch 3 Cycle</p>
                </div>
                <span class="rounded-md border border-white/[0.08] bg-white/[0.03] px-3 py-1 font-mono text-xs font-bold text-slate-300">Active</span>
            </div>

            <div class="mt-5 space-y-4">
                @foreach ($milestones as $index => $milestone)
                    <div class="grid grid-cols-[2rem_1fr] gap-3">
                        <div class="flex flex-col items-center">
                            <div @class([
                                'flex h-8 w-8 items-center justify-center rounded-md border text-xs font-black',
                                'border-white/20 bg-white/10 text-slate-100' => $index <= 1,
                                'border-white/15 bg-transparent text-slate-500' => $index > 1,
                            ])>{{ $index + 1 }}</div>
                            @if (! $loop->last)
                                <div class="mt-2 h-full min-h-6 w-px bg-white/[0.07]"></div>
                            @endif
                        </div>
                        <div class="pb-1">
                            <p class="text-sm font-bold text-white">{{ $milestone['date'] }}</p>
                            <p class="mt-1 text-sm leading-6 text-slate-400">{{ $milestone['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="grid gap-4 lg:grid-cols-[1fr_1fr]">
            <div class="rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-black text-white">Settlement Coordinates</p>
                        <p class="mt-1 text-xs text-slate-500">Disbursement</p>
                    </div>
                    <span class="rounded-md border border-white/[0.08] bg-white/[0.03] px-3 py-1 text-xs font-bold text-slate-300">Synced</span>
                </div>

                <dl class="mt-5 divide-y divide-white/[0.07]">
                    <div class="grid gap-1 py-3 first:pt-0">
                        <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Recipient</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['recipient'] }}</dd>
                    </div>
                    <div class="grid gap-1 py-3">
                        <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Address</dt>
                        <dd class="break-words font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['address'] }}</dd>
                    </div>
                    <div class="grid gap-1 py-3 last:pb-0">
                        <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Destination</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['destination'] }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-black text-white">Contract Footprint</p>
                        <p class="mt-1 text-xs text-slate-500">Records</p>
                    </div>
                    <span class="rounded-md border border-white/[0.08] bg-white/[0.03] px-3 py-1 text-xs font-bold uppercase tracking-[0.12em] text-slate-300">Read Only</span>
                </div>

                <div class="mt-5 space-y-3">
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.025] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Agreement</p>
                        <p class="mt-2 font-mono text-sm leading-6 text-slate-200">{{ $panel['documents']['line_1'] }}</p>
                    </div>
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.025] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Registration</p>
                        <p class="mt-2 font-mono text-sm leading-6 text-slate-200">{{ $panel['documents']['line_2'] }}</p>
                    </div>
                </div>

                <div class="mt-5 divide-y divide-white/[0.07]">
                    @foreach ($panel['history'] as $history)
                        <div class="grid gap-1 py-3 first:pt-0 last:pb-0">
                            <div class="flex items-start justify-between gap-3">
                                <p class="text-sm font-bold text-white">{{ $history['record'] }}</p>
                                <p class="shrink-0 font-mono text-xs text-slate-500">{{ $history['date'] }}</p>
                            </div>
                            <p class="font-mono text-sm leading-6 text-slate-400">{{ $history['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
</x-dashboard.shell>
