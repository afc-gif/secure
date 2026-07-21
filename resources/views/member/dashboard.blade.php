<x-dashboard.shell title="Member Portfolio" eyebrow="Verified Member Dashboard">
    @php
        $panel = $individualPanel;
        $milestones = $panel['milestones'];
        $isLocked = ! $dashboardUnlocked;
        $paymentToken = $paymentToken ?? null;
        $lockedPanelClasses = $isLocked ? 'pointer-events-none select-none blur-sm' : '';
        $withdrawalStatus = $panel['disbursement']['withdrawal_status'];
        $withdrawalHref = in_array($withdrawalStatus, ['processing', 'completed'], true)
            ? route('member.settlement-profile.withdrawal-status')
            : route('member.settlement-profile.show');
        $withdrawalLabel = match (true) {
            in_array($withdrawalStatus, ['processing', 'completed'], true) => 'Track Withdrawal',
            $panel['disbursement']['bank_ready'] => 'Withdraw to Bank',
            default => 'Add Bank Details',
        };
    @endphp

    @if (session('locked'))
        <div class="mb-5 rounded-lg border border-[#d8bf7a]/25 bg-[#d8bf7a]/10 px-5 py-4 text-sm font-semibold text-[#fff0bf]">{{ session('locked') }}</div>
    @endif

    <div class="grid gap-4 lg:gap-5 xl:grid-cols-[minmax(0,1fr)_19rem]">
        <section class="relative overflow-hidden rounded-lg border border-white/[0.08] bg-[#151018] p-5 shadow-2xl shadow-black/25 sm:p-7">
            <div class="{{ $lockedPanelClasses }}">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_12%_15%,rgba(255,255,255,0.16),transparent_18rem),linear-gradient(135deg,#7c3cff_0%,#d936ad_48%,#f35f8d_100%)]"></div>
            <div class="absolute -right-12 bottom-0 h-40 w-40 rounded-full border border-white/15 bg-white/[0.05]"></div>
            <div class="relative">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.13em] text-white/70">Secured Benefit Balance</p>
                    <h2 class="mt-3 text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl">{{ $panel['balance']['formatted_available'] }}</h2>
                    <p class="mt-4 max-w-xl text-sm leading-6 text-white/75">
                        @if ($isLocked)
                            Crypto payment is pending admin-approved VIP activation. Enter the issued token to open the full dashboard.
                        @elseif ($panel['balance']['processing_withdrawal'] > 0)
                            Withdrawal of {{ $panel['balance']['formatted_processing_withdrawal'] }} is processing. Available balance has been updated.
                        @else
                            Legally verified carried contract allocation synchronized to the active Batch 3 member cycle.
                        @endif
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    @if (! $isLocked)
                        <a href="{{ $withdrawalHref }}" class="inline-flex items-center rounded-md border border-white/20 bg-white px-3 py-1.5 text-xs font-bold uppercase tracking-[0.12em] text-[#151018] transition hover:bg-white/90">
                            {{ $withdrawalLabel }}
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center rounded-md border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.12em] text-white transition hover:bg-white/15">Edit Profile</a>
                    <span class="inline-flex items-center gap-2 rounded-md border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.12em] text-white">
                        <span class="h-1.5 w-1.5 rounded-full {{ $isLocked ? 'bg-[#d8bf7a]' : 'bg-white' }}"></span>
                        {{ $isLocked ? 'VIP Locked' : 'Verified' }}
                    </span>
                </div>
            </div>

            @if ($isLocked)
                <div class="mt-6 rounded-lg border border-[#d8bf7a]/20 bg-[#08090c]/70 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#fff0bf]/70">Unlock Dashboard</p>
                    @if ($paymentToken)
                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Required Amount</p>
                                <p class="mt-2 font-mono text-xl font-black text-white">{{ $paymentToken->price_currency }} {{ number_format((float) $paymentToken->price, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">BTC Wallet</p>
                                <p class="mt-2 break-all font-mono text-sm text-slate-300">{{ $paymentToken->btc_wallet_address }}</p>
                            </div>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-slate-400">Send the unlock amount to the wallet above, then submit the payment reference on the unlock page to activate your dashboard.</p>
                    @else
                        <p class="mt-2 text-sm leading-6 text-slate-400">Admin has not created a VIP unlock payment yet. Once a payment setup is added, the amount and wallet will appear here.</p>
                    @endif
                </div>
            @endif

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
            </div>
            @if ($isLocked)
                <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-[#07080b]/45 p-4 backdrop-blur-[1px]">
                    <a href="{{ route('member.access-token.create') }}" class="rounded-md border border-[#d8bf7a]/25 bg-[#d8bf7a]/15 px-4 py-2 text-xs font-black uppercase tracking-[0.12em] text-[#fff0bf]">VIP token required</a>
                </div>
            @endif
        </section>

        <section class="relative">
            <div class="{{ $lockedPanelClasses }} grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
            <div class="rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20">
                <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Member Credential</p>
                @if ($isLocked)
                    <p class="mt-3 text-sm font-black uppercase leading-6 text-white">VIP Token Required</p>
                    <a href="{{ route('member.access-token.create') }}" class="cca-button mt-4 w-full py-2 text-xs">Enter Token</a>
                @else
                    <p class="mt-3 break-all font-mono text-sm font-black leading-6 text-white">{{ $panel['gate']['access_input'] }}</p>
                    <p class="mt-3 max-w-full break-all rounded-md border border-white/[0.06] bg-white/[0.02] px-3 py-2 font-mono text-xs text-slate-500">{{ $panel['gate']['variable'] }}</p>
                @endif
            </div>

            <div class="rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20">
                <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Status</p>
                <p class="mt-3 text-sm font-black uppercase leading-6 text-white">{{ $isLocked ? 'Crypto approval and VIP token required' : 'Legally Verified / Carried Contract Allocation' }}</p>
                <div @class([
                    'mt-4 rounded-md border px-3 py-2 text-xs font-bold uppercase tracking-[0.12em]',
                    'border-[#d8bf7a]/25 bg-[#d8bf7a]/10 text-[#fff0bf]' => $isLocked,
                    'border-[#f35aa5]/20 bg-[#f35aa5]/10 text-[#ffd4e9]' => ! $isLocked,
                ])>{{ $isLocked ? 'Locked' : 'Active' }}</div>
            </div>
            </div>
            @if ($isLocked)
                <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-[#07080b]/45 p-4 backdrop-blur-[1px]">
                    <a href="{{ route('member.access-token.create') }}" class="rounded-md border border-[#d8bf7a]/25 bg-[#d8bf7a]/15 px-4 py-2 text-xs font-black uppercase tracking-[0.12em] text-[#fff0bf]">VIP token required</a>
                </div>
            @endif
        </section>

        <section class="relative rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-6 xl:col-span-1">
            <div class="{{ $lockedPanelClasses }}">
            <div class="flex items-center justify-between gap-3">
                <p class="text-sm font-black text-white">Allocation Split</p>
                <span class="text-xs font-semibold text-slate-500">Batch 3 Cycle</span>
            </div>

            <div class="mt-5 grid gap-3 md:grid-cols-3">
                @foreach ($panel['dataBlocks'] as $block)
                    <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10]/80 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <p class="text-sm font-bold text-white">{{ $block['header'] }}</p>
                            <span @class([
                                'rounded-md border px-2 py-1 text-[0.65rem] font-black uppercase tracking-[0.12em]',
                                'border-emerald-300/20 bg-emerald-400/10 text-emerald-100' => strtolower($block['status'] ?? '') === 'cleared',
                                'border-[#d8bf7a]/25 bg-[#d8bf7a]/10 text-[#fff0bf]' => strtolower($block['status'] ?? '') === 'pending',
                            ])>{{ $block['status'] ?? 'Active' }}</span>
                        </div>
                        <p class="mt-2 text-xs uppercase leading-5 tracking-[0.12em] text-slate-500">{{ $block['label'] }}</p>
                        <p class="mt-5 text-xl font-black leading-tight text-white">{{ $block['allocation'] }}</p>
                    </div>
                @endforeach
            </div>
            </div>
            @if ($isLocked)
                <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-[#07080b]/45 p-4 backdrop-blur-[1px]">
                    <a href="{{ route('member.access-token.create') }}" class="rounded-md border border-[#d8bf7a]/25 bg-[#d8bf7a]/15 px-4 py-2 text-xs font-black uppercase tracking-[0.12em] text-[#fff0bf]">VIP token required</a>
                </div>
            @endif
        </section>

        <section class="relative rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-6 xl:row-span-2">
            <div class="{{ $lockedPanelClasses }}">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-black text-white">Timeline</p>
                    <p class="mt-1 text-xs text-slate-500">Batch 3 Cycle</p>
                </div>
                <span class="rounded-md border border-emerald-300/20 bg-emerald-400/10 px-3 py-1 font-mono text-xs font-bold text-emerald-100">Active</span>
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
            </div>
            @if ($isLocked)
                <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-[#07080b]/45 p-4 backdrop-blur-[1px]">
                    <a href="{{ route('member.access-token.create') }}" class="rounded-md border border-[#d8bf7a]/25 bg-[#d8bf7a]/15 px-4 py-2 text-xs font-black uppercase tracking-[0.12em] text-[#fff0bf]">VIP token required</a>
                </div>
            @endif
        </section>

        <section class="grid gap-4 lg:grid-cols-[1fr_1fr]">
            <div class="rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-black text-white">Payout Profile</p>
                    </div>
                </div>

                <dl class="mt-5 divide-y divide-white/[0.07]">
                    <div class="grid gap-1 py-3 first:pt-0">
                        <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Available Balance</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['balance']['formatted_available'] }}</dd>
                    </div>
                    <div class="grid gap-1 py-3">
                        <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Withdrawn</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['balance']['formatted_completed_withdrawals'] }}</dd>
                    </div>
                    @if ($panel['balance']['processing_withdrawal'] > 0)
                        <div class="grid gap-1 py-3">
                            <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Processing Withdrawal</dt>
                            <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['balance']['formatted_processing_withdrawal'] }}</dd>
                        </div>
                    @endif
                    <div class="grid gap-1 py-3">
                        <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Name</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['recipient'] }}</dd>
                    </div>
                    <div class="grid gap-1 py-3">
                        <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Phone Number</dt>
                        <dd class="font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['phone'] }}</dd>
                    </div>
                    <div class="grid gap-1 py-3 last:pb-0">
                        <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Registered Address</dt>
                        <dd class="break-words font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['address'] }}</dd>
                    </div>
                    @if ($panel['disbursement']['bank_name'])
                        <div class="grid gap-1 py-3 last:pb-0">
                            <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Bank</dt>
                            <dd class="break-words font-mono text-sm leading-6 text-slate-200">{{ $panel['disbursement']['bank_name'] }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="relative rounded-lg border border-white/[0.07] bg-[#101116]/95 p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="{{ $lockedPanelClasses }}">
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
                @if ($isLocked)
                    <div class="absolute inset-0 flex items-center justify-center rounded-lg bg-[#07080b]/45 p-4 backdrop-blur-[1px]">
                        <a href="{{ route('member.access-token.create') }}" class="rounded-md border border-[#d8bf7a]/25 bg-[#d8bf7a]/15 px-4 py-2 text-xs font-black uppercase tracking-[0.12em] text-[#fff0bf]">VIP token required</a>
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-dashboard.shell>
