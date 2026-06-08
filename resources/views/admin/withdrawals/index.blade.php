<x-dashboard.shell title="Withdrawal management" eyebrow="Member Payouts">
    <div class="grid gap-3 sm:grid-cols-2 sm:gap-4 xl:grid-cols-4">
        <x-dashboard.stat-card label="Processing Requests" :value="$processingCount" detail="Submitted by members" tone="gold" />
        <x-dashboard.stat-card label="Processing Amount" value="USD {{ number_format((float) $processingTotal, 2) }}" detail="Awaiting payout" />
        <x-dashboard.stat-card label="Completed Requests" :value="$completedCount" detail="Finished withdrawals" tone="slate" />
        <x-dashboard.stat-card label="Total Withdrawn" value="USD {{ number_format((float) $completedTotal, 2) }}" detail="Recorded member payouts" />
    </div>

    <section class="cca-card mt-4 p-4 sm:mt-6 sm:p-5">
        <form method="GET" action="{{ route('admin.withdrawals.index') }}" class="grid gap-3 md:grid-cols-4">
            <input name="search" value="{{ $filters['search'] ?? '' }}" class="rounded-lg border-white/10 bg-white/[0.06] text-white md:col-span-2" placeholder="Search member, bank, or account name">
            <select name="status" class="rounded-lg border-white/10 bg-white/[0.06] text-white">
                <option class="bg-[#0b1110]" value="">All status</option>
                @foreach (['processing', 'completed'] as $status)
                    <option class="bg-[#0b1110]" value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ Str::title($status) }}</option>
                @endforeach
            </select>
            <button class="cca-button">Filter</button>
        </form>
    </section>

    <section class="cca-card mt-4 overflow-hidden sm:mt-6">
        <div class="flex flex-col gap-3 border-b border-white/10 px-4 py-4 sm:flex-row sm:items-end sm:justify-between sm:px-5">
            <div class="min-w-0">
                <h2 class="text-lg font-black text-white">Withdrawal queue</h2>
                <p class="mt-1 text-sm text-slate-500">Review member payout requests and bank withdrawal details.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="cca-muted-button w-full sm:w-auto">Admin Dashboard</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[76rem] divide-y divide-white/10 text-left text-sm">
                <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Member</th>
                        <th class="px-5 py-4">Amount</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Bank Details</th>
                        <th class="px-5 py-4">Requested</th>
                        <th class="px-5 py-4">Completed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse ($withdrawals as $withdrawal)
                        <tr class="transition hover:bg-white/[0.04]">
                            <td class="max-w-[16rem] px-5 py-4">
                                <p class="font-semibold text-white">{{ $withdrawal->user?->memberProfile?->full_legal_name ?? $withdrawal->user?->name ?? 'Member unavailable' }}</p>
                                <p class="mt-1 break-all text-xs text-slate-500">{{ $withdrawal->user?->email ?? 'Email unavailable' }}</p>
                                @if ($withdrawal->user?->reference_token)
                                    <p class="mt-1 font-mono text-xs text-[#ffd4e9]">{{ $withdrawal->user->reference_token }}</p>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 font-mono font-black text-slate-100">USD {{ number_format((float) $withdrawal->withdrawal_amount, 2) }}</td>
                            <td class="px-5 py-4"><x-ownership.status-badge :status="$withdrawal->withdrawal_status" /></td>
                            <td class="max-w-[24rem] px-5 py-4">
                                <p class="font-semibold text-white">{{ $withdrawal->bank_name ?: 'Bank pending' }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ $withdrawal->account_name ?: 'Account name pending' }}</p>
                                <p class="mt-1 font-mono text-xs text-slate-500">Acct {{ $withdrawal->account_number ?: 'pending' }} / Routing {{ $withdrawal->routing_number ?: 'pending' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ Str::of($withdrawal->account_type ?: 'account')->title() }} / {{ $withdrawal->currency ?: 'USD' }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <p class="font-semibold text-white">{{ $withdrawal->withdrawal_requested_at?->format('M j, Y g:i A') ?? 'Not recorded' }}</p>
                                @if ($withdrawal->withdrawal_requested_at)
                                    <p class="mt-1 text-xs text-slate-500">{{ $withdrawal->withdrawal_requested_at->diffForHumans() }}</p>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                @if ($withdrawal->withdrawal_completed_at)
                                    <p class="font-semibold text-white">{{ $withdrawal->withdrawal_completed_at->format('M j, Y g:i A') }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $withdrawal->withdrawal_completed_at->diffForHumans() }}</p>
                                @else
                                    <span class="text-slate-500">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-6 text-slate-500" colspan="6">No withdrawal requests match the current filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-white/10 px-4 py-4 sm:px-5">{{ $withdrawals->withQueryString()->links() }}</div>
    </section>
</x-dashboard.shell>
