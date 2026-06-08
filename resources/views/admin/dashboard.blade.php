<x-dashboard.shell title="Super Admin" eyebrow="Administration">
    <div class="space-y-4 sm:space-y-6">
        <section class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/25 sm:p-6 lg:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Control Center</p>
                    <h2 class="mt-2 text-2xl font-black leading-tight text-white sm:text-4xl">Manage members, batches, payments, and dashboard access</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-400">Use VIP Payment Setup to set the unlock price and Bitcoin wallet shown to locked members. Use Payment Reviews to approve submitted payments and activate access.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 lg:w-[24rem]">
                    <a href="{{ route('admin.tokens.create') }}" class="cca-button text-center">Add Payment Setup</a>
                    <a href="{{ route('admin.contributions.pending') }}" class="cca-muted-button text-center">Review Payments</a>
                    <a href="{{ route('admin.withdrawals.index', ['status' => 'processing']) }}" class="cca-muted-button text-center sm:col-span-2">Review Withdrawals</a>
                </div>
            </div>
        </section>

        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <x-dashboard.stat-card label="Active Members" :value="$activeMembers" detail="Member accounts enabled" />
            <x-dashboard.stat-card label="Completed Onboarding" :value="$completedOnboarding" detail="Ready for dashboard unlock" tone="gold" />
            <x-dashboard.stat-card label="Active Batches" :value="$activeBatches" detail="Open participation cycles" />
            <x-dashboard.stat-card label="Pending Payments" :value="$pendingPayments" detail="Awaiting admin review" tone="slate" />
            <x-dashboard.stat-card label="Withdrawal Requests" :value="$processingWithdrawals" detail="Awaiting payout awareness" tone="gold" />
        </section>

        <section class="grid gap-4 lg:grid-cols-[1fr_1fr]">
            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Payment Operations</p>
                        <h3 class="mt-2 text-xl font-black text-white">Unlock payments</h3>
                    </div>
                    <a href="{{ route('admin.contributions.index') }}" class="cca-muted-button">Open Reviews</a>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Pending Amount</p>
                        <p class="mt-3 font-mono text-2xl font-black text-white">USD {{ number_format((float) $pendingPaymentTotal, 2) }}</p>
                    </div>
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Confirmed Amount</p>
                        <p class="mt-3 font-mono text-2xl font-black text-white">USD {{ number_format((float) $confirmedPaymentTotal, 2) }}</p>
                    </div>
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Pending Reviews</p>
                        <p class="mt-3 font-mono text-2xl font-black text-white">{{ $pendingPayments }}</p>
                    </div>
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Approved Payments</p>
                        <p class="mt-3 font-mono text-2xl font-black text-white">{{ $confirmedPayments }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Dashboard Access</p>
                        <h3 class="mt-2 text-xl font-black text-white">VIP unlock status</h3>
                    </div>
                    <a href="{{ route('admin.tokens.index') }}" class="cca-muted-button">Open Setup</a>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Active Payment Setups</p>
                        <p class="mt-3 font-mono text-2xl font-black text-white">{{ $activeTokens }}</p>
                    </div>
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Unlocked Members</p>
                        <p class="mt-3 font-mono text-2xl font-black text-white">{{ $usedTokens }}</p>
                    </div>
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Batch Participants</p>
                        <p class="mt-3 font-mono text-2xl font-black text-white">{{ $totalParticipants }}</p>
                    </div>
                    <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                        <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Incomplete Onboarding</p>
                        <p class="mt-3 font-mono text-2xl font-black text-white">{{ $pendingOnboarding }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/20 sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Withdrawal Operations</p>
                    <h3 class="mt-2 text-xl font-black text-white">Member withdrawal requests</h3>
                    <p class="mt-2 text-sm text-slate-500">Processing requests are listed here so admins can see payout activity as soon as members submit it.</p>
                </div>
                <a href="{{ route('admin.withdrawals.index') }}" class="cca-muted-button">Open Withdrawals</a>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Processing Amount</p>
                    <p class="mt-3 font-mono text-2xl font-black text-white">USD {{ number_format((float) $processingWithdrawalTotal, 2) }}</p>
                </div>
                <div class="rounded-lg border border-white/[0.07] bg-white/[0.035] p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Processing Requests</p>
                    <p class="mt-3 font-mono text-2xl font-black text-white">{{ $processingWithdrawals }}</p>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="min-w-[48rem] divide-y divide-white/10 text-left text-sm">
                    <thead class="text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="py-3 pr-5">Member</th>
                            <th class="px-5 py-3">Amount</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="pl-5 py-3">Requested</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-slate-300">
                        @forelse ($latestWithdrawals as $withdrawal)
                            <tr>
                                <td class="max-w-[14rem] py-4 pr-5 font-semibold text-white">{{ $withdrawal->user?->name ?? 'Member unavailable' }}</td>
                                <td class="whitespace-nowrap px-5 py-4 font-mono text-slate-100">USD {{ number_format((float) $withdrawal->withdrawal_amount, 2) }}</td>
                                <td class="px-5 py-4"><x-ownership.status-badge :status="$withdrawal->withdrawal_status" /></td>
                                <td class="whitespace-nowrap py-4 pl-5">{{ $withdrawal->withdrawal_requested_at?->diffForHumans() ?? 'Not recorded' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="py-5 text-slate-500" colspan="4">No withdrawal requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-[1fr_1fr]">
            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Members</p>
                        <h3 class="mt-2 text-xl font-black text-white">Latest completed onboarding</h3>
                    </div>
                    <a href="{{ route('admin.partners.index') }}" class="cca-muted-button">Open Members</a>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-[36rem] divide-y divide-white/10 text-left text-sm">
                        <thead class="text-xs uppercase tracking-[0.12em] text-slate-500">
                            <tr>
                                <th class="py-3 pr-5">Member</th>
                                <th class="px-5 py-3">Reference</th>
                                <th class="pl-5 py-3">Completed</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 text-slate-300">
                            @forelse ($latestOnboarded as $profile)
                                <tr>
                                    <td class="max-w-[14rem] py-4 pr-5 font-semibold text-white">{{ $profile->full_legal_name }}</td>
                                    <td class="px-5 py-4 font-mono text-xs text-slate-300">{{ $profile->user->reference_token }}</td>
                                    <td class="whitespace-nowrap py-4 pl-5">{{ $profile->onboarding_completed_at?->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-5 text-slate-500" colspan="3">No completed onboarding records yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10] p-4 shadow-xl shadow-black/20 sm:p-6">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Participation</p>
                        <h3 class="mt-2 text-xl font-black text-white">Latest dashboard unlocks</h3>
                    </div>
                    <a href="{{ route('admin.batches.index') }}" class="cca-muted-button">Open Batches</a>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-[40rem] divide-y divide-white/10 text-left text-sm">
                        <thead class="text-xs uppercase tracking-[0.12em] text-slate-500">
                            <tr>
                                <th class="py-3 pr-5">Member</th>
                                <th class="px-5 py-3">Batch</th>
                                <th class="pl-5 py-3">Unlocked</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10 text-slate-300">
                            @forelse ($latestJoins as $join)
                                <tr>
                                    <td class="max-w-[14rem] py-4 pr-5 font-semibold text-white">{{ $join->user?->name }}</td>
                                    <td class="max-w-[14rem] px-5 py-4">{{ $join->batch?->title }}</td>
                                    <td class="whitespace-nowrap py-4 pl-5">{{ $join->joined_at?->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-5 text-slate-500" colspan="3">No dashboard unlocks yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</x-dashboard.shell>
