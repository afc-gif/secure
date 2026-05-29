<x-dashboard.shell title="Ownership contributions" eyebrow="Cooperative Ledger">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card label="Total Contributions" value="{{ $summary['currency'] ?? 'USD' }} {{ number_format($summary['confirmed_total'], 2) }}" detail="Confirmed cooperative pool value" />
        <x-dashboard.stat-card label="Ownership Acre" value="{{ number_format($summary['ownership_percentage'], 2) }}%" detail="Your share of confirmed assets" tone="gold" />
        <x-dashboard.stat-card label="Participation Score" value="{{ $summary['participation_score'] }}/100" detail="Onboarding, batch, and contribution activity" />
        <x-dashboard.stat-card label="Settlement Readiness" value="{{ $summary['settlement_eligible'] ? 'Ready' : 'Building' }}" detail="Verified profile plus confirmed pool activity" tone="slate" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.25fr_0.75fr]">
        <section class="cca-card overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-white/10 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-black text-white">Contribution ledger</h2>
                    <p class="mt-1 text-sm text-slate-500">Track Harvest Cycle requests and approval status.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('member.contributions.history') }}" class="cca-muted-button">History</a>
                    <a href="{{ route('member.contributions.create') }}" class="cca-button">New Contribution</a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                    <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.18em] text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Reference</th>
                            <th class="px-5 py-4">Type</th>
                            <th class="px-5 py-4">Amount</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Cycle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-slate-300">
                        @forelse ($contributions as $contribution)
                            <tr class="transition hover:bg-white/[0.04]">
                                <td class="px-5 py-4"><a class="font-mono text-xs font-bold text-[#d4af62]" href="{{ route('member.contributions.show', $contribution) }}">{{ $contribution->payment_reference }}</a></td>
                                <td class="px-5 py-4 font-semibold text-white">{{ $contribution->getTypeLabel() }}</td>
                                <td class="px-5 py-4">{{ $contribution->currency }} {{ number_format((float) $contribution->amount, 2) }}</td>
                                <td class="px-5 py-4"><x-ownership.status-badge :status="$contribution->status" /></td>
                                <td class="px-5 py-4">{{ $contribution->batch?->title ?? 'General pool' }}</td>
                            </tr>
                        @empty
                            <tr><td class="px-5 py-6 text-slate-500" colspan="5">No contribution requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-white/10 px-5 py-4">{{ $contributions->links() }}</div>
        </section>

        <section class="space-y-4">
            <div class="cca-card p-5">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Ownership Progress</p>
                <h3 class="mt-3 text-2xl font-black text-white">{{ number_format($summary['ownership_percentage'], 2) }}%</h3>
                <div class="mt-5 h-2 rounded-full bg-white/10">
                    <div class="h-2 rounded-full bg-gradient-to-r from-emerald-400 to-[#d4af62]" style="width: {{ min(100, max(4, $summary['ownership_percentage'])) }}%"></div>
                </div>
                <p class="mt-3 text-sm text-slate-400">Your confirmed contributions as a share of the cooperative asset pool.</p>
            </div>

            <div class="cca-card p-5">
                <h3 class="font-black text-white">Recent cooperative activity</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($recentActivity as $activity)
                        <div class="rounded-lg border border-white/10 bg-white/[0.04] p-3">
                            <p class="text-sm font-bold text-white">{{ Str::of($activity->action)->replace('_', ' ')->title() }}</p>
                            <p class="mt-1 text-xs leading-5 text-slate-400">{{ $activity->description }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No activity logged yet.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-dashboard.shell>
