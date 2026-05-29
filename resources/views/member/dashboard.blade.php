<x-dashboard.shell title="Member ownership workspace" eyebrow="Partner Dashboard">
    <section class="cca-card overflow-hidden p-6 sm:p-8">
        <div class="grid gap-6 lg:grid-cols-[1fr_18rem] lg:items-center">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.22em] text-emerald-300">Welcome back</p>
                <h2 class="mt-3 text-3xl font-black text-white">{{ $profile->full_legal_name }}, your ownership profile is synchronized.</h2>
                <p class="mt-4 max-w-2xl text-slate-400">Your CCA member identity, address registry, and cooperative profile are active for countryside ownership participation.</p>
            </div>
            <div class="rounded-lg border border-[#d4af62]/20 bg-[#d4af62]/10 p-5">
                <p class="text-xs uppercase tracking-[0.22em] text-[#d4af62]">Reference Token</p>
                <p class="mt-3 font-mono text-xl font-black text-white">{{ auth()->user()->reference_token }}</p>
                <p class="mt-2 text-sm text-slate-400">Linked to your synchronized member profile.</p>
            </div>
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-3">
        <x-profile.summary-card label="Ownership Status" value="Verified Member" tone="emerald" detail="Your identity and residential registry are synchronized for cooperative access." />

        @php($activeParticipation = $participations->firstWhere('participation_status', 'active'))

        <section class="cca-card p-6">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Active Batch Preview</p>
            <h3 class="mt-4 text-2xl font-black text-white">{{ $activeParticipation?->batch->title ?? 'Awaiting Access Token' }}</h3>
            <div class="mt-5 h-2 rounded-full bg-white/10">
                <div class="h-2 rounded-full bg-gradient-to-r from-emerald-400 to-[#d4af62]" style="width: {{ $activeParticipation ? $activeParticipation->batch->progressPercentage() : 12 }}%"></div>
            </div>
            <p class="mt-3 text-sm text-slate-400">{{ $activeParticipation ? 'Participation activated for this ownership cycle.' : 'Enter a cooperative access token to join an active cycle.' }}</p>
        </section>

        <section class="cca-card p-6">
            <div class="flex items-start justify-between gap-4">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Profile Completion</p>
                <x-profile.status-badge tone="emerald">Verified</x-profile.status-badge>
            </div>
            <h3 class="mt-4 text-2xl font-black text-[#d4af62]">{{ $completion }}%</h3>
            <div class="mt-5 h-2 rounded-full bg-white/10">
                <div class="h-2 rounded-full bg-gradient-to-r from-emerald-400 to-[#d4af62]" style="width: {{ $completion }}%"></div>
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-400">Identity, address, and cooperative profile synchronized.</p>
        </section>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card label="Total Contributions" value="USD {{ number_format($ownership['confirmed_total'], 2) }}" detail="Confirmed cooperative capital" />
        <x-dashboard.stat-card label="Ownership Percentage" value="{{ number_format($ownership['ownership_percentage'], 2) }}%" detail="Share of confirmed pool" tone="gold" />
        <x-dashboard.stat-card label="Active Batches" value="{{ $participations->where('participation_status', 'active')->count() }}" detail="Activated Harvest Cycles" />
        <x-dashboard.stat-card label="Participation Score" value="{{ $ownership['participation_score'] }}/100" detail="Contribution and batch activity" tone="gold" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1fr_1fr]">
        <section class="cca-card p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Ownership Intelligence</p>
                    <h3 class="mt-2 text-xl font-black text-white">Cooperative Yield Position</h3>
                </div>
                <span class="text-lg font-black text-[#d4af62]">{{ number_format($ownership['ownership_percentage'], 2) }}%</span>
            </div>
            <div class="mt-5 h-3 rounded-full bg-white/10">
                <div class="h-3 rounded-full bg-gradient-to-r from-emerald-400 to-[#d4af62]" style="width: {{ min(100, max(4, $ownership['ownership_percentage'])) }}%"></div>
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-400">Calculated from your confirmed contributions divided by the confirmed cooperative pool.</p>
        </section>

        <section class="cca-card p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Contribution Timeline</p>
                    <h3 class="mt-2 text-xl font-black text-white">Recent ownership activity</h3>
                </div>
                <a href="{{ route('member.contributions.index') }}" class="cca-muted-button">Open Ledger</a>
            </div>
            <div class="mt-5 space-y-3">
                @forelse ($recentContributions as $contribution)
                    <div class="flex items-center justify-between gap-4 rounded-lg border border-white/10 bg-white/[0.04] p-3">
                        <div>
                            <p class="text-sm font-bold text-white">{{ $contribution->getTypeLabel() }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $contribution->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-[#d4af62]">{{ $contribution->currency }} {{ number_format((float) $contribution->amount, 2) }}</p>
                            <x-ownership.status-badge :status="$contribution->status" class="mt-1" />
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No contribution activity yet.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[0.8fr_1.2fr]">
        <section class="cca-card p-6">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Cooperative Member Summary</p>
            <dl class="mt-5 space-y-4 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Interest</dt><dd class="font-semibold text-white">{{ Str::of($profile->agricultural_interest_type)->replace('_', ' ')->title() }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Occupation</dt><dd class="font-semibold text-white">{{ $profile->occupation }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Registry City</dt><dd class="font-semibold text-white">{{ $profile->city }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-slate-500">Ownership Tier</dt><dd class="font-semibold text-white">{{ $activeParticipation ? Str::of($activeParticipation->accessToken->ownership_tier)->replace('_', ' ')->title() : 'Not activated' }}</dd></div>
            </dl>
        </section>

        <section class="cca-card p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Ownership Verification</p>
                    <h3 class="mt-2 text-xl font-black text-white">Secure member registry active</h3>
                </div>
                <x-profile.status-badge tone="gold">Synchronized</x-profile.status-badge>
            </div>
            <p class="mt-4 text-sm leading-6 text-slate-400">{{ $activeParticipation ? 'Your access token has been validated and linked to an active ownership cycle.' : 'Your verified profile is ready for token-based batch participation.' }}</p>
            <div class="mt-5">
                <a href="{{ route('member.access-token.create') }}" class="cca-muted-button">Manage Access Token</a>
            </div>
        </section>
    </div>

    <div class="mt-6">
        <x-ownership.table :participations="$participations" />
    </div>

    <section class="cca-card mt-6 p-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Milestone Tracker</p>
                <h3 class="mt-2 text-xl font-black text-white">Timeline preview</h3>
            </div>
            <span class="text-sm font-semibold text-emerald-300">Phase 2 preview</span>
        </div>
        <div class="mt-6 grid gap-4 sm:grid-cols-4">
            @foreach (['Onboarded', 'Batch Assigned', 'Cultivation', 'Yield Report'] as $index => $step)
                <div class="rounded-lg border {{ $index === 0 ? 'border-emerald-300/30 bg-emerald-300/10' : 'border-white/10 bg-white/[0.04]' }} p-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg {{ $index === 0 ? 'bg-emerald-300 text-[#08100c]' : 'bg-white/10 text-slate-400' }} text-sm font-black">{{ $index + 1 }}</div>
                    <p class="mt-4 text-sm font-bold text-white">{{ $step }}</p>
                </div>
            @endforeach
        </div>
    </section>
</x-dashboard.shell>
