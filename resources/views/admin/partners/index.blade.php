<x-dashboard.shell title="Partner registry" eyebrow="Member Records">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-dashboard.stat-card label="Total Partners" :value="$totalPartners" detail="Registered member accounts" />
        <x-dashboard.stat-card label="Active Partners" :value="$activePartners" detail="Accounts in good standing" tone="gold" />
        <x-dashboard.stat-card label="Completed Profiles" :value="$completedProfiles" detail="Synchronized member records" />
        <x-dashboard.stat-card label="Pending Profiles" :value="$pendingProfiles" detail="Incomplete registry records" tone="slate" />
    </div>

    <section class="cca-card mt-6 overflow-hidden">
        <div class="border-b border-white/10 px-5 py-4">
            <h2 class="text-lg font-black text-white">Partner records</h2>
            <p class="mt-1 text-sm text-slate-500">Member identity, US address registry, settlement status, and confirmed USD contribution totals.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.18em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Partner</th>
                        <th class="px-5 py-4">Reference</th>
                        <th class="px-5 py-4">Registry Address</th>
                        <th class="px-5 py-4">Profile</th>
                        <th class="px-5 py-4">Settlement</th>
                        <th class="px-5 py-4">Confirmed</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse ($partners as $partner)
                        @php($profile = $partner->memberProfile)
                        @php($settlement = $partner->settlementProfile)
                        <tr class="transition hover:bg-white/[0.04]">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-white">{{ $profile?->full_legal_name ?? $partner->name }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $partner->email }}</p>
                            </td>
                            <td class="px-5 py-4 font-mono text-xs text-[#d4af62]">{{ $partner->reference_token }}</td>
                            <td class="px-5 py-4">
                                @if ($profile)
                                    <p class="font-semibold text-white">{{ $profile->city ?: 'City pending' }}{{ $profile->state ? ', '.$profile->state : '' }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $profile->country ?: 'United States' }}{{ $profile->postal_code ? ' '.$profile->postal_code : '' }}</p>
                                @else
                                    <span class="text-slate-500">Not started</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if ($profile?->onboarding_completed)
                                    <x-profile.status-badge tone="emerald">Complete</x-profile.status-badge>
                                @else
                                    <x-profile.status-badge tone="gold">{{ $profile?->completionPercentage() ?? 0 }}%</x-profile.status-badge>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <x-ownership.status-badge :status="$settlement?->verification_status ?? 'pending'" />
                            </td>
                            <td class="px-5 py-4 font-black text-[#d4af62]">USD {{ number_format((float) ($partner->confirmed_contributions_total ?? 0), 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-6 text-slate-500" colspan="6">No partner records have been created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-6">{{ $partners->links() }}</div>
</x-dashboard.shell>
