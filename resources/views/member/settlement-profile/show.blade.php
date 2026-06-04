<x-dashboard.shell title="Cash App Withdrawal" eyebrow="Member Payouts">
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-[#f35aa5]/25 bg-[#f35aa5]/10 px-5 py-4 text-sm font-semibold text-[#ffd4e9]">{{ session('success') }}</div>
    @endif

    <div class="grid gap-5 xl:grid-cols-[0.85fr_1.15fr]">
        <section class="cca-card p-5 sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="cca-kicker">Withdrawal Method</p>
                    <h2 class="mt-3 text-2xl font-black text-white">Cash App only</h2>
                </div>
                <x-ownership.status-badge :status="$profile->verification_status ?? 'pending'" />
            </div>

            <dl class="mt-6 divide-y divide-white/[0.07]">
                <div class="grid gap-1 py-3 first:pt-0">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Platform</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">Cash App</dd>
                </div>
                <div class="grid gap-1 py-3">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Cash App Handle</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ $profile->cash_app_handle ?: 'Not provided' }}</dd>
                </div>
                <div class="grid gap-1 py-3 last:pb-0">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Currency</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">USD</dd>
                </div>
            </dl>
        </section>

        <section class="cca-card p-5 sm:p-6">
            <p class="cca-kicker">Withdrawal Setup</p>
            <h2 class="mt-3 text-2xl font-black text-white">{{ $profile->exists ? 'Update Cash App details' : 'Add Cash App details' }}</h2>
            <p class="mt-3 text-sm leading-6 text-slate-400">Members can withdraw only through Cash App. Enter the Cash App handle that should receive payout review.</p>

            <form method="POST" action="{{ $profile->exists ? route('member.settlement-profile.update', $profile) : route('member.settlement-profile.store') }}" class="mt-6 space-y-5">
                @csrf
                @if ($profile->exists)
                    @method('PATCH')
                @endif

                <div>
                    <label for="cash_app_handle" class="cca-label">Cash App Handle</label>
                    <input id="cash_app_handle" name="cash_app_handle" value="{{ old('cash_app_handle', $profile->cash_app_handle) }}" class="cca-input mt-2 font-mono" placeholder="$YourHandle" required autocomplete="off">
                    <p class="mt-2 text-xs leading-5 text-slate-500">Example: $YourHandle. No bank, card, crypto, or alternate payout method is accepted.</p>
                    <x-input-error :messages="$errors->get('cash_app_handle')" class="mt-2 text-rose-300" />
                </div>

                <div class="flex flex-col gap-3 border-t border-white/10 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('member.dashboard') }}" class="cca-muted-button text-center">Back to Dashboard</a>
                    <button class="cca-button">{{ $profile->exists ? 'Save Cash App' : 'Add Cash App' }}</button>
                </div>
            </form>
        </section>
    </div>
</x-dashboard.shell>
