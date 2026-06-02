<x-dashboard.shell title="Member Benefit Vault" eyebrow="Verified Member Dashboard">
    @php
        $panel = $individualPanel;
        $milestones = $panel['milestones'];
    @endphp

    <div class="space-y-6">
        <section class="overflow-hidden rounded-lg border border-white/10 bg-black shadow-2xl shadow-black/40">
            <div class="grid gap-0 lg:grid-cols-[1.05fr_0.95fr]">
                <div class="p-6 sm:p-8 lg:p-10">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-300/25 bg-emerald-300/10 px-4 py-2 text-sm font-bold text-emerald-100">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-300 shadow-[0_0_16px_rgba(110,231,183,0.9)]"></span>
                            Access Verified
                        </span>
                        <span class="rounded-full border border-white/10 bg-white/[0.06] px-4 py-2 font-mono text-sm text-slate-300">{{ $panel['gate']['variable'] }}</span>
                    </div>

                    <h2 class="mt-7 max-w-3xl text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl">Total secured benefit balance</h2>
                    <p class="mt-5 font-mono text-3xl font-black tracking-tight text-[#d4af62] sm:text-5xl">USD 33,000.00</p>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-400">Legally verified carried contract allocation synchronized to the active Batch 3 member cycle.</p>

                    <div class="mt-8 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-lg border border-white/10 bg-white/[0.045] p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Member Credential</p>
                            <p class="mt-3 font-mono text-xl font-black text-white">{{ $panel['gate']['access_input'] }}</p>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-white/[0.045] p-4">
                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Verification Status</p>
                            <p class="mt-3 text-sm font-black uppercase leading-6 text-emerald-200">Legally Verified / Carried Contract Allocation</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/10 bg-white/[0.035] p-6 sm:p-8 lg:border-l lg:border-t-0 lg:p-10">
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d4af62]">Allocation Split</p>
                    <div class="mt-6 space-y-4">
                        @foreach ($panel['dataBlocks'] as $block)
                            <div class="rounded-lg border border-white/10 bg-black/35 p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-sm font-black text-white">{{ $block['label'] }}</p>
                                        <p class="mt-2 font-mono text-sm leading-6 text-slate-400">{{ $block['header'] }}</p>
                                    </div>
                                    <p class="shrink-0 font-mono text-lg font-black text-[#d4af62]">{{ $block['allocation'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[0.9fr_1.1fr]">
            <div class="rounded-lg border border-white/10 bg-black p-6 shadow-2xl shadow-black/30 sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d4af62]">Read-Only Profile Box</p>
                        <h3 class="mt-2 text-2xl font-black text-white">Secure Disbursement Coordinates</h3>
                    </div>
                    <span class="rounded-full border border-emerald-300/25 bg-emerald-300/10 px-3 py-1 text-xs font-bold text-emerald-100">Synchronized</span>
                </div>

                <dl class="mt-7 space-y-5">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Registered Recipient</dt>
                        <dd class="mt-2 rounded-lg bg-white/[0.055] px-4 py-3 font-mono text-lg text-slate-200">{{ $panel['disbursement']['recipient'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Physical Footprint Address</dt>
                        <dd class="mt-2 rounded-lg bg-white/[0.055] px-4 py-3 font-mono text-lg leading-7 text-slate-200">{{ $panel['disbursement']['address'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Settlement Destination</dt>
                        <dd class="mt-2 rounded-lg bg-white/[0.055] px-4 py-3 font-mono text-lg text-slate-200">{{ $panel['disbursement']['destination'] }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-lg border border-white/10 bg-black p-6 shadow-2xl shadow-black/30 sm:p-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d4af62]">Operational Timeline</p>
                        <h3 class="mt-2 text-2xl font-black text-white">Batch 3 Synchronization Cycle</h3>
                    </div>
                    <span class="rounded-full border border-white/10 bg-white/[0.06] px-3 py-1 font-mono text-xs font-bold text-slate-300">Active</span>
                </div>

                <div class="mt-9">
                    <div class="relative hidden grid-cols-3 gap-4 sm:grid">
                        <div class="absolute left-[12%] right-[12%] top-5 h-1 rounded-full bg-white/10"></div>
                        <div class="absolute left-[12%] top-5 h-1 w-[38%] rounded-full bg-gradient-to-r from-emerald-300 to-[#d4af62]"></div>
                        @foreach ($milestones as $index => $milestone)
                            <div class="relative z-[1] text-center">
                                <div @class([
                                    'mx-auto flex h-11 w-11 items-center justify-center rounded-full border text-sm font-black',
                                    'border-emerald-200 bg-emerald-200 text-black shadow-[0_0_24px_rgba(110,231,183,0.35)]' => $index <= 1,
                                    'border-white/20 bg-black text-slate-500' => $index > 1,
                                ])>{{ $index + 1 }}</div>
                                <p class="mt-4 text-sm font-black text-white">{{ $milestone['date'] }}</p>
                                <p class="mt-2 text-sm leading-6 text-slate-400">{{ $milestone['label'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="space-y-4 sm:hidden">
                        @foreach ($milestones as $index => $milestone)
                            <div class="flex gap-4 rounded-lg border border-white/10 bg-white/[0.04] p-4">
                                <div @class([
                                    'flex h-10 w-10 shrink-0 items-center justify-center rounded-full border text-sm font-black',
                                    'border-emerald-200 bg-emerald-200 text-black' => $index <= 1,
                                    'border-white/20 bg-black text-slate-500' => $index > 1,
                                ])>{{ $index + 1 }}</div>
                                <div>
                                    <p class="text-sm font-black text-white">{{ $milestone['date'] }}</p>
                                    <p class="mt-1 text-sm leading-6 text-slate-400">{{ $milestone['label'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-white/10 bg-black p-6 shadow-2xl shadow-black/30 sm:p-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.24em] text-[#d4af62]">Contractual Vault</p>
                    <h3 class="mt-2 text-2xl font-black text-white">Historical Footprint</h3>
                </div>
                <span class="rounded-full border border-white/10 bg-white/[0.06] px-3 py-1 text-xs font-bold uppercase tracking-[0.16em] text-slate-300">Read Only</span>
            </div>

            <div class="mt-7 grid gap-4 lg:grid-cols-2">
                <div class="rounded-lg border border-white/10 bg-white/[0.04] p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Document Line 1</p>
                    <p class="mt-3 font-mono text-base leading-7 text-slate-200">{{ $panel['documents']['line_1'] }}</p>
                </div>
                <div class="rounded-lg border border-white/10 bg-white/[0.04] p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Document Line 2</p>
                    <p class="mt-3 font-mono text-base leading-7 text-slate-200">{{ $panel['documents']['line_2'] }}</p>
                </div>
            </div>

            <div class="mt-7 space-y-4">
                @foreach ($panel['history'] as $history)
                    <div class="grid gap-4 rounded-lg border border-white/10 bg-white/[0.04] p-5 sm:grid-cols-[10rem_1fr] sm:items-start">
                        <div>
                            <p class="text-sm font-black text-white">{{ $history['record'] }}</p>
                            <p class="mt-2 inline-flex rounded-full bg-white/[0.07] px-3 py-1 font-mono text-xs text-slate-300">{{ $history['date'] }}</p>
                        </div>
                        <p class="font-mono text-base leading-7 text-slate-300">{{ $history['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-dashboard.shell>
