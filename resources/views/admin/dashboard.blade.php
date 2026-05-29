<x-dashboard.shell title="Administrative command center" eyebrow="Operations Ledger">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card label="Active Members" :value="$activeMembers" detail="Registered cooperative accounts" />
        <x-dashboard.stat-card label="Completed Onboarding" :value="$completedOnboarding" detail="Synchronized ownership profiles" />
        <x-dashboard.stat-card label="Pending Onboarding" :value="$pendingOnboarding" detail="Incomplete member profiles" tone="gold" />
        <x-dashboard.stat-card label="Open Batches" :value="$activeBatches" detail="Current countryside cycles" />
        <x-dashboard.stat-card label="Participants" :value="$totalParticipants" detail="Activated batch memberships" />
        <x-dashboard.stat-card label="Active Tokens" :value="$activeTokens" detail="Available cooperative tokens" tone="gold" />
        <x-dashboard.stat-card label="Used Tokens" :value="$usedTokens" detail="Token usage analytics" tone="slate" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <section class="cca-card overflow-hidden">
            <div class="border-b border-white/10 px-5 py-4">
                <h2 class="text-lg font-black text-white">Latest onboarded members</h2>
                <p class="mt-1 text-sm text-slate-500">Completed identity and ownership profile synchronizations.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                    <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.18em] text-slate-500">
                        <tr>
                            <th class="px-5 py-4">Partner</th>
                            <th class="px-5 py-4">Token</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4">Completed</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-slate-300">
                        @forelse ($latestOnboarded as $profile)
                            <tr class="transition hover:bg-white/[0.04]">
                                <td class="px-5 py-4 font-semibold text-white">{{ $profile->full_legal_name }}</td>
                                <td class="px-5 py-4 font-mono text-xs text-[#d4af62]">{{ $profile->user->reference_token }}</td>
                                <td class="px-5 py-4">
                                    <x-profile.status-badge>Onboarded</x-profile.status-badge>
                                </td>
                                <td class="px-5 py-4">{{ $profile->onboarding_completed_at?->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-5 py-6 text-slate-500" colspan="4">No completed onboarding records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="space-y-4">
            <div class="cca-card p-5">
                <h3 class="font-black text-white">Latest batch joins</h3>
                <p class="mt-1 text-sm text-slate-500">Recent ownership cycle activations.</p>
            </div>
            @forelse ($latestJoins as $join)
                <div class="cca-card p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="font-black text-white">{{ $join->user->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $join->batch->title }}</p>
                        </div>
                        <x-ownership.status-badge :status="$join->participation_status" />
                    </div>
                </div>
            @empty
                <div class="cca-card p-5 text-sm text-slate-500">No batch joins yet.</div>
            @endforelse

            <div class="cca-card p-5">
                <h3 class="font-black text-white">Ownership distribution</h3>
                <div class="mt-4 space-y-3">
                    @forelse ($ownershipDistribution as $tier)
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-slate-300">{{ Str::of($tier->ownership_tier)->replace('_', ' ')->title() }}</span>
                            <span class="font-black text-[#d4af62]">{{ $tier->aggregate }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No token usage distribution yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="cca-card p-5">
                <h3 class="font-black text-white">Incomplete profiles</h3>
                <p class="mt-1 text-sm text-slate-500">Members currently blocked from the full dashboard.</p>
            </div>

            @forelse ($incompleteProfiles as $member)
                <div class="cca-card p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="font-black text-white">{{ $member->name }}</h3>
                            <p class="mt-1 text-sm text-slate-500">{{ $member->email }}</p>
                        </div>
                        <span class="text-lg font-black text-[#d4af62]">{{ $member->memberProfile?->completionPercentage() ?? 0 }}%</span>
                    </div>
                    <div class="mt-4 h-2 rounded-full bg-white/10">
                        <div class="h-2 rounded-full bg-gradient-to-r from-emerald-400 to-[#d4af62]" style="width: {{ $member->memberProfile?->completionPercentage() ?? 0 }}%"></div>
                    </div>
                </div>
            @empty
                <div class="cca-card p-5 text-sm text-slate-500">All current members have completed onboarding.</div>
            @endforelse
        </section>
    </div>
</x-dashboard.shell>
