<x-dashboard.shell title="Partner registry" eyebrow="Member Records">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
    @endif

    <div class="grid gap-3 sm:grid-cols-2 sm:gap-4 xl:grid-cols-4">
        <x-dashboard.stat-card label="Signed Up Members" :value="$totalPartners" detail="Every completed signup account" />
        <x-dashboard.stat-card label="Active Accounts" :value="$activePartners" detail="Accounts in good standing" tone="gold" />
        <x-dashboard.stat-card label="VIP Participants" :value="$activeParticipants" detail="Members with active cycle access" />
        <x-dashboard.stat-card label="Completed Profiles" :value="$completedProfiles" detail="Synchronized member records" />
    </div>

    <section class="cca-card mt-4 overflow-hidden sm:mt-6">
        <div class="border-b border-white/10 px-4 py-4 sm:px-5">
            <h2 class="text-lg font-black text-white">Signed up members and participants</h2>
            <p class="mt-1 text-sm text-slate-500">Every member account created through signup, including VIP participation status, profile status, and confirmed USD contribution totals.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-[96rem] divide-y divide-white/10 text-left text-sm">
                <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Member</th>
                        <th class="px-5 py-4">Login Token</th>
                        <th class="px-5 py-4">Signup</th>
                        <th class="px-5 py-4">Contact</th>
                        <th class="px-5 py-4">Registry Address</th>
                        <th class="px-5 py-4">Profile</th>
                        <th class="px-5 py-4">Participant Access</th>
                        <th class="px-5 py-4">Settlement</th>
                        <th class="px-5 py-4">Confirmed</th>
                        <th class="px-5 py-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse ($partners as $partner)
                        @php($profile = $partner->memberProfile)
                        @php($settlement = $partner->settlementProfile)
                        @php($latestParticipation = $partner->batchMembers->sortByDesc('joined_at')->first())
                        <tr class="transition hover:bg-white/[0.04]">
                            <td class="max-w-[16rem] px-5 py-4">
                                <p class="font-semibold text-white">{{ $profile?->full_legal_name ?? $partner->name }}</p>
                                <p class="mt-1 break-all text-xs text-slate-500">{{ $partner->email }}</p>
                                <p class="mt-1 text-xs text-slate-500">User ID: {{ $partner->id }}</p>
                            </td>
                            <td class="max-w-[15rem] px-5 py-4">
                                <p class="font-mono text-xs font-black text-[#ffd4e9]">{{ $partner->reference_token }}</p>
                                <p class="mt-1 text-xs text-slate-500">Required at member login</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <p class="font-semibold text-white">{{ $partner->created_at->format('M j, Y') }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $partner->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="max-w-[13rem] px-5 py-4">
                                <p class="break-all text-xs font-semibold text-white">{{ $partner->phone ?: 'Phone pending' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ Str::of($partner->status)->title() }}</p>
                                @if ($profile?->date_of_birth)
                                    <p class="mt-1 text-xs text-slate-500">DOB {{ $profile->date_of_birth->format('M j, Y') }}</p>
                                @endif
                            </td>
                            <td class="max-w-[14rem] px-5 py-4">
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
                            <td class="max-w-[16rem] px-5 py-4">
                                @if ($latestParticipation)
                                    <x-profile.status-badge tone="emerald">Participant</x-profile.status-badge>
                                    <div class="mt-2 space-y-2">
                                        @foreach ($partner->batchMembers->sortByDesc('joined_at') as $participation)
                                            <div class="rounded-md border border-white/[0.07] bg-white/[0.025] p-2">
                                                <p class="text-xs font-semibold text-white">{{ $participation->batch?->title ?? 'VIP cycle' }}</p>
                                                <p class="mt-1 font-mono text-xs text-[#ffd4e9]">{{ $participation->accessToken?->token ?? 'Token unavailable' }}</p>
                                                <p class="mt-1 text-xs text-slate-500">{{ Str::of($participation->participation_status)->title() }}{{ $participation->joined_at ? ' / '.$participation->joined_at->format('M j, Y') : '' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <x-profile.status-badge tone="gold">Signed Up</x-profile.status-badge>
                                    <p class="mt-2 text-xs text-slate-500">Awaiting VIP payment/token activation</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <x-ownership.status-badge :status="$settlement?->verification_status ?? 'pending'" />
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 font-black text-slate-100">USD {{ number_format((float) ($partner->confirmed_contributions_total ?? 0), 2) }}</td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}" onsubmit="return confirm('Delete this member and their related member records? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg border border-rose-400/30 bg-rose-500/10 px-3 py-2 text-xs font-black uppercase tracking-[0.12em] text-rose-100 transition hover:border-rose-300/60 hover:bg-rose-500/20">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-6 text-slate-500" colspan="10">No member signups have been created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div class="mt-6">{{ $partners->links() }}</div>
</x-dashboard.shell>
