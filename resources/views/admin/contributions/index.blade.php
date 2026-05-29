<x-dashboard.shell title="Contribution management" eyebrow="Ownership Intelligence">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <x-dashboard.stat-card label="Total Contributions" value="USD {{ number_format($stats['total_contributions'], 2) }}" detail="Confirmed cooperative pool" />
        <x-dashboard.stat-card label="Pending" value="USD {{ number_format($stats['pending_contributions'], 2) }}" detail="Awaiting admin review" tone="gold" />
        <x-dashboard.stat-card label="Confirmed Assets" value="USD {{ number_format($stats['confirmed_assets'], 2) }}" detail="Cooperative asset base" />
        <x-dashboard.stat-card label="Monthly Growth" value="{{ $stats['monthly_growth'] }}%" detail="Current month trend" tone="slate" />
        <x-dashboard.stat-card label="Active Rate" value="{{ $stats['active_participation_rate'] }}%" detail="Members in active cycles" />
    </div>

    <section class="cca-card mt-6 p-5">
        <form method="GET" action="{{ route('admin.contributions.index') }}" class="grid gap-3 md:grid-cols-7">
            <input name="search" value="{{ $filters['search'] ?? '' }}" class="rounded-lg border-white/10 bg-white/[0.06] text-white md:col-span-2" placeholder="Search member or reference">
            <select name="status" class="rounded-lg border-white/10 bg-white/[0.06] text-white">
                <option class="bg-[#0b1110]" value="">All status</option>
                @foreach (['pending', 'confirmed', 'rejected'] as $status)
                    <option class="bg-[#0b1110]" value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ Str::title($status) }}</option>
                @endforeach
            </select>
            <select name="contribution_type" class="rounded-lg border-white/10 bg-white/[0.06] text-white">
                <option class="bg-[#0b1110]" value="">All types</option>
                @foreach ($types as $type)
                    <option class="bg-[#0b1110]" value="{{ $type }}" @selected(($filters['contribution_type'] ?? '') === $type)>{{ Str::of($type)->replace('_', ' ')->title() }}</option>
                @endforeach
            </select>
            <select name="batch_id" class="rounded-lg border-white/10 bg-white/[0.06] text-white">
                <option class="bg-[#0b1110]" value="">All cycles</option>
                @foreach ($batches as $batch)
                    <option class="bg-[#0b1110]" value="{{ $batch->id }}" @selected((string) ($filters['batch_id'] ?? '') === (string) $batch->id)>{{ $batch->title }}</option>
                @endforeach
            </select>
            <select name="member_id" class="rounded-lg border-white/10 bg-white/[0.06] text-white">
                <option class="bg-[#0b1110]" value="">All members</option>
                @foreach ($members as $member)
                    <option class="bg-[#0b1110]" value="{{ $member->id }}" @selected((string) ($filters['member_id'] ?? '') === (string) $member->id)>{{ $member->name }}</option>
                @endforeach
            </select>
            <button class="cca-button">Filter</button>
        </form>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <section class="cca-card overflow-hidden">
            <div class="flex flex-col gap-2 border-b border-white/10 px-5 py-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-lg font-black text-white">Contribution review queue</h2>
                    <p class="mt-1 text-sm text-slate-500">Approve or reject member ownership requests.</p>
                </div>
                <a href="{{ route('admin.contributions.pending') }}" class="cca-muted-button">Pending Queue</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                    <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.18em] text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Member</th>
                            <th class="px-5 py-4">Reference</th>
                            <th class="px-5 py-4">Amount</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Submitted</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-slate-300">
                        @forelse ($contributions as $contribution)
                            <tr class="transition hover:bg-white/[0.04]">
                                <td class="px-5 py-4 font-semibold text-white">{{ $contribution->user->name }}</td>
                                <td class="px-5 py-4"><a class="font-mono text-xs text-[#d4af62]" href="{{ route('admin.contributions.show', $contribution) }}">{{ $contribution->payment_reference }}</a></td>
                                <td class="px-5 py-4">{{ $contribution->currency }} {{ number_format((float) $contribution->amount, 2) }}</td>
                                <td class="px-5 py-4"><x-ownership.status-badge :status="$contribution->status" /></td>
                                <td class="px-5 py-4">{{ $contribution->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr><td class="px-5 py-6 text-slate-500" colspan="5">No contribution records match the current filters.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-white/10 px-5 py-4">{{ $contributions->withQueryString()->links() }}</div>
        </section>

        <section class="space-y-4">
            <div class="cca-card p-5">
                <h3 class="font-black text-white">Top contributors</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($stats['top_contributors'] as $member)
                        <div class="flex items-center justify-between gap-4 rounded-lg border border-white/10 bg-white/[0.04] p-3">
                            <span class="font-semibold text-white">{{ $member->name }}</span>
                            <span class="text-sm font-black text-[#d4af62]">USD {{ number_format((float) $member->confirmed_contributions_total, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No confirmed contributors yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="cca-card p-5">
                <h3 class="font-black text-white">Monthly cooperative growth</h3>
                <div class="mt-5">
                    <x-analytics.bar-list :items="$stats['monthly_contributions']" />
                </div>
            </div>
        </section>
    </div>
</x-dashboard.shell>
