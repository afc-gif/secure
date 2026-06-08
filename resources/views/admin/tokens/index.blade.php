<x-dashboard.shell title="VIP Payment Setup" eyebrow="Super Admin">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
    @endif
    <x-input-error :messages="$errors->get('token')" class="mb-6 text-rose-300" />

    <div class="grid gap-3 sm:grid-cols-3 sm:gap-4">
        <x-dashboard.stat-card label="Active Setups" :value="$activeTokens" detail="Shown to locked members" />
        <x-dashboard.stat-card label="Activated Members" :value="$usedTokens" detail="Approved and unlocked" tone="gold" />
        <x-dashboard.stat-card label="Disabled Setups" :value="$revokedTokens" detail="No longer available" tone="slate" />
    </div>

    <div class="my-4 flex sm:my-6 sm:justify-end">
        <a href="{{ route('admin.tokens.create') }}" class="cca-button w-full sm:w-auto">Add Payment Setup</a>
    </div>

    <section class="cca-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-[76rem] divide-y divide-white/10 text-left text-sm">
                <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Setup ID</th>
                        <th class="px-5 py-4">Batch</th>
                        <th class="px-5 py-4">Access Level</th>
                        <th class="px-5 py-4">Price</th>
                        <th class="px-5 py-4">Bitcoin Wallet</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Member</th>
                        <th class="px-5 py-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse ($tokens as $token)
                        <tr class="transition hover:bg-white/[0.04]">
                            <td class="px-5 py-4 font-mono text-xs text-slate-300">{{ $token->token }}</td>
                            <td class="max-w-[14rem] px-5 py-4 font-semibold text-white">{{ $token->batch?->title ?? 'No batch' }}</td>
                            <td class="px-5 py-4">{{ $token->ownership_tier }}</td>
                            <td class="px-5 py-4 font-mono text-xs font-bold text-[#d8bf7a]">{{ $token->price ? $token->price_currency.' '.number_format((float) $token->price, 2) : 'Unset' }}</td>
                            <td class="max-w-[16rem] break-all px-5 py-4 font-mono text-xs">{{ $token->btc_wallet_address ?? 'Unset' }}</td>
                            <td class="px-5 py-4"><x-ownership.status-badge :status="$token->status" /></td>
                            <td class="max-w-[14rem] px-5 py-4">{{ $token->assignedUser?->name ?? 'Unassigned' }}</td>
                            <td class="px-5 py-4">
                                @if ($token->status === 'active')
                                    <form method="POST" action="{{ route('admin.tokens.revoke', $token) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="cca-muted-button">Revoke</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-500">No action</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-5 py-6 text-slate-500" colspan="8">No VIP payment setup has been created yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="mt-6">{{ $tokens->links() }}</div>
</x-dashboard.shell>
