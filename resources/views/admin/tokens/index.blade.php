<x-dashboard.shell title="Access token management" eyebrow="Secure Token Ledger">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
    @endif
    <x-input-error :messages="$errors->get('token')" class="mb-6 text-rose-300" />

    <div class="grid gap-4 sm:grid-cols-3">
        <x-dashboard.stat-card label="Active Tokens" :value="$activeTokens" detail="Available for validation" />
        <x-dashboard.stat-card label="Used Tokens" :value="$usedTokens" detail="Activated participation" tone="gold" />
        <x-dashboard.stat-card label="Revoked Tokens" :value="$revokedTokens" detail="Removed from circulation" tone="slate" />
    </div>

    <div class="my-6 flex justify-end">
        <a href="{{ route('admin.tokens.create') }}" class="cca-button">Generate Token</a>
    </div>

    <section class="cca-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.18em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Token</th>
                        <th class="px-5 py-4">Batch</th>
                        <th class="px-5 py-4">Tier</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Assigned</th>
                        <th class="px-5 py-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse ($tokens as $token)
                        <tr class="transition hover:bg-white/[0.04]">
                            <td class="px-5 py-4 font-mono text-xs text-[#d4af62]">{{ $token->token }}</td>
                            <td class="px-5 py-4 font-semibold text-white">{{ $token->batch->title }}</td>
                            <td class="px-5 py-4">{{ Str::of($token->ownership_tier)->title() }}</td>
                            <td class="px-5 py-4"><x-ownership.status-badge :status="$token->status" /></td>
                            <td class="px-5 py-4">{{ $token->assignedUser?->name ?? 'Unassigned' }}</td>
                            <td class="px-5 py-4">
                                @if ($token->status === 'active')
                                    <form method="POST" action="{{ route('admin.tokens.revoke', $token) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button class="cca-muted-button">Revoke</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-500">Locked</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-5 py-6 text-slate-500" colspan="6">No access tokens generated yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="mt-6">{{ $tokens->links() }}</div>
</x-dashboard.shell>
