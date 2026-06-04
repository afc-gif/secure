<x-dashboard.shell title="Secure Access Token Management" eyebrow="Secure Token Ledger">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
    @endif
    <x-input-error :messages="$errors->get('token')" class="mb-6 text-rose-300" />

    <div class="grid gap-3 sm:grid-cols-3 sm:gap-4">
        <x-dashboard.stat-card label="Active VIP Tokens" :value="$activeTokens" detail="Available for secure validation" />
        <x-dashboard.stat-card label="Used VIP Tokens" :value="$usedTokens" detail="Granted dashboard privilege" tone="gold" />
        <x-dashboard.stat-card label="Revoked Tokens" :value="$revokedTokens" detail="Removed from circulation" tone="slate" />
    </div>

    <div class="my-4 flex sm:my-6 sm:justify-end">
        <a href="{{ route('admin.tokens.create') }}" class="cca-button w-full sm:w-auto">Generate Secure Token</a>
    </div>

    <section class="cca-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-[58rem] divide-y divide-white/10 text-left text-sm">
                <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.12em] text-slate-500">
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
                            <td class="px-5 py-4 font-mono text-xs text-slate-300">{{ $token->token }}</td>
                            <td class="max-w-[14rem] px-5 py-4 font-semibold text-white">Batch 3 Entertainment Cycle</td>
                            <td class="px-5 py-4">Batch 3 Synchronized Class</td>
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
                                    <span class="text-xs text-slate-500">Locked</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td class="px-5 py-6 text-slate-500" colspan="6">No secure access tokens generated yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="mt-6">{{ $tokens->links() }}</div>
</x-dashboard.shell>
