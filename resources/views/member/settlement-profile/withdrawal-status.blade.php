<x-dashboard.shell title="Withdrawal Status" eyebrow="Member Payouts">
    @php
        $isComplete = $profile->withdrawal_status === 'completed';
        $requestedAt = $profile->withdrawal_requested_at;
        $estimatedCompletion = $requestedAt?->copy()->addDay();
    @endphp

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-[#f35aa5]/25 bg-[#f35aa5]/10 px-5 py-4 text-sm font-semibold text-[#ffd4e9]">{{ session('success') }}</div>
    @endif

    <div class="grid gap-5 xl:grid-cols-[1fr_0.75fr]">
        <section class="cca-card overflow-hidden">
            <div class="border-b border-white/[0.07] bg-white/[0.015] p-5 sm:p-7">
                <p class="cca-kicker">Bank Transfer</p>
                <h2 class="mt-3 text-3xl font-black text-white">{{ $isComplete ? 'Withdrawal Complete' : 'Withdrawal Processing' }}</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">
                    {{ $isComplete ? 'Your withdrawal has been completed.' : 'Your withdrawal will be complete within 24hrs.' }}
                </p>
            </div>

            <div class="p-5 sm:p-7">
                <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10]/70 p-4 sm:p-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-500">Current Status</p>
                            <p class="mt-2 text-xl font-black text-white">{{ $isComplete ? 'Complete' : 'Processing' }}</p>
                        </div>
                        <x-ownership.status-badge :status="$isComplete ? 'completed' : 'processing'" />
                    </div>
                </div>

                <div class="mt-6 grid gap-4">
                    <div class="grid grid-cols-[2rem_1fr] gap-3">
                        <div class="flex flex-col items-center">
                            <div class="flex h-8 w-8 items-center justify-center rounded-md border border-[#f35aa5]/25 bg-[#f35aa5]/10 text-xs font-black text-[#ffd4e9]">1</div>
                            <div class="mt-2 h-full min-h-6 w-px bg-[#f35aa5]/25"></div>
                        </div>
                        <div class="pb-2">
                            <p class="text-sm font-bold text-white">Withdrawal submitted</p>
                            <p class="mt-1 text-sm leading-6 text-slate-400">{{ $requestedAt?->format('F j, Y g:i A') ?? 'Submitted' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-[2rem_1fr] gap-3">
                        <div class="flex flex-col items-center">
                            <div @class([
                                'flex h-8 w-8 items-center justify-center rounded-md border text-xs font-black',
                                'border-[#f35aa5]/25 bg-[#f35aa5]/10 text-[#ffd4e9]' => ! $isComplete,
                                'border-emerald-300/25 bg-emerald-300/10 text-emerald-100' => $isComplete,
                            ])>2</div>
                            <div @class([
                                'mt-2 h-full min-h-6 w-px',
                                'bg-white/[0.07]' => ! $isComplete,
                                'bg-emerald-300/25' => $isComplete,
                            ])></div>
                        </div>
                        <div class="pb-2">
                            <p class="text-sm font-bold text-white">Bank processing</p>
                            <p class="mt-1 text-sm leading-6 text-slate-400">
                                {{ $isComplete ? 'Processing finished.' : 'Funds are being routed through bank processing.' }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-[2rem_1fr] gap-3">
                        <div class="flex flex-col items-center">
                            <div @class([
                                'flex h-8 w-8 items-center justify-center rounded-md border text-xs font-black',
                                'border-white/15 bg-transparent text-slate-500' => ! $isComplete,
                                'border-emerald-300/25 bg-emerald-300/10 text-emerald-100' => $isComplete,
                            ])>3</div>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-white">Withdrawal complete</p>
                            <p class="mt-1 text-sm leading-6 text-slate-400">
                                @if ($isComplete)
                                    Completed {{ $profile->withdrawal_completed_at?->format('F j, Y g:i A') }}.
                                @else
                                    Estimated completion: {{ $estimatedCompletion?->format('F j, Y g:i A') ?? 'within 24hrs' }}.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-7 flex flex-col gap-3 border-t border-white/10 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('member.dashboard') }}" class="cca-button text-center">Back to Dashboard</a>
                    <a href="{{ route('member.settlement-profile.show') }}" class="cca-muted-button text-center">Withdrawal Details</a>
                </div>
            </div>
        </section>

        <section class="cca-card p-5 sm:p-6">
            <p class="cca-kicker">Destination</p>
            <h3 class="mt-3 text-xl font-black text-white">{{ $profile->bank_name }}</h3>
            <dl class="mt-5 divide-y divide-white/[0.07]">
                <div class="grid gap-1 py-3 first:pt-0">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Account Holder</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ $profile->account_name }}</dd>
                </div>
                <div class="grid gap-1 py-3">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Account Type</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ Str::of($profile->account_type)->title() }}</dd>
                </div>
                <div class="grid gap-1 py-3">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Routing Number</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ $profile->routing_number }}</dd>
                </div>
                <div class="grid gap-1 py-3 last:pb-0">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Tracking</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ $isComplete ? 'Complete' : 'Processing' }}</dd>
                </div>
            </dl>
        </section>
    </div>
</x-dashboard.shell>
