<x-dashboard.shell title="Bank Withdrawal" eyebrow="Member Payouts">
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-[#f35aa5]/25 bg-[#f35aa5]/10 px-5 py-4 text-sm font-semibold text-[#ffd4e9]">{{ session('success') }}</div>
    @endif

    <div class="grid gap-5 xl:grid-cols-[0.85fr_1.15fr]">
        <section class="cca-card p-5 sm:p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="cca-kicker">Withdrawal Method</p>
                    <h2 class="mt-3 text-2xl font-black text-white">Bank account</h2>
                </div>
                <x-ownership.status-badge :status="$profile->verification_status ?? 'pending'" />
            </div>

            <dl class="mt-6 divide-y divide-white/[0.07]">
                <div class="grid gap-1 py-3 first:pt-0">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Bank Name</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ $profile->bank_name ?: 'Not provided' }}</dd>
                </div>
                <div class="grid gap-1 py-3">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Account Holder Name</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ $profile->account_name ?: 'Not provided' }}</dd>
                </div>
                <div class="grid gap-1 py-3">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Routing Number</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ $profile->routing_number ?: 'Not provided' }}</dd>
                </div>
                <div class="grid gap-1 py-3">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Account Number</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ $profile->account_number ?: 'Not provided' }}</dd>
                </div>
                <div class="grid gap-1 py-3">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Account Type</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">{{ $profile->account_type ? Str::of($profile->account_type)->title() : 'Not provided' }}</dd>
                </div>
                <div class="grid gap-1 py-3 last:pb-0">
                    <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Currency</dt>
                    <dd class="font-mono text-sm leading-6 text-slate-200">USD</dd>
                </div>
            </dl>
        </section>

        <section class="cca-card p-5 sm:p-6">
            <p class="cca-kicker">Withdrawal Setup</p>
            <h2 class="mt-3 text-2xl font-black text-white">{{ $profile->exists ? 'Update bank details' : 'Add bank details' }}</h2>
            <p class="mt-3 text-sm leading-6 text-slate-400">Enter the bank account details that should receive payout review.</p>

            @if ($hasBankDetails)
                <div class="mt-6 rounded-lg border border-white/[0.07] bg-white/[0.025] p-4">
                    @if ($profile->withdrawal_status === 'completed')
                        <p class="text-xs font-black uppercase tracking-[0.14em] text-[#ffd4e9]">Withdrawal Complete</p>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Your withdrawal has been completed.</p>
                        <a href="{{ route('member.settlement-profile.withdrawal-status') }}" class="cca-button mt-4 inline-flex w-full justify-center sm:w-auto">Track Withdrawal</a>
                    @elseif ($profile->withdrawal_status === 'processing')
                        <p class="text-xs font-black uppercase tracking-[0.14em] text-[#ffd4e9]">Processing</p>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Your withdrawal will be complete within 24hrs.</p>
                        <a href="{{ route('member.settlement-profile.withdrawal-status') }}" class="cca-button mt-4 inline-flex w-full justify-center sm:w-auto">Track Withdrawal</a>
                    @else
                        <p class="text-xs font-black uppercase tracking-[0.14em] text-slate-500">Ready</p>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Your bank details are saved. Proceed when you are ready to start withdrawal processing.</p>
                        <form method="POST" action="{{ route('member.settlement-profile.withdraw') }}" class="mt-4">
                            @csrf
                            <button class="cca-button w-full sm:w-auto">Proceed to Withdraw</button>
                        </form>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ $profile->exists ? route('member.settlement-profile.update', $profile) : route('member.settlement-profile.store') }}" class="mt-6 space-y-5">
                @csrf
                @if ($profile->exists)
                    @method('PATCH')
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="bank_name" class="cca-label">Bank Name</label>
                        <input id="bank_name" name="bank_name" value="{{ old('bank_name', $profile->bank_name) }}" class="cca-input mt-2" required autocomplete="off">
                        <x-input-error :messages="$errors->get('bank_name')" class="mt-2 text-rose-300" />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="account_name" class="cca-label">Account Holder Name</label>
                        <input id="account_name" name="account_name" value="{{ old('account_name', $profile->account_name) }}" class="cca-input mt-2" required autocomplete="off">
                        <x-input-error :messages="$errors->get('account_name')" class="mt-2 text-rose-300" />
                    </div>

                    <div>
                        <label for="routing_number" class="cca-label">Routing Number</label>
                        <input id="routing_number" name="routing_number" value="{{ old('routing_number', $profile->routing_number) }}" class="cca-input mt-2 font-mono" inputmode="numeric" maxlength="9" required autocomplete="off">
                        <x-input-error :messages="$errors->get('routing_number')" class="mt-2 text-rose-300" />
                    </div>

                    <div>
                        <label for="account_number" class="cca-label">Account Number</label>
                        <input id="account_number" name="account_number" value="{{ old('account_number', $profile->account_number) }}" class="cca-input mt-2 font-mono" required autocomplete="off">
                        <x-input-error :messages="$errors->get('account_number')" class="mt-2 text-rose-300" />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="account_type" class="cca-label">Account Type</label>
                        <select id="account_type" name="account_type" class="cca-input mt-2" required>
                            <option value="">Select account type</option>
                            <option value="checking" @selected(old('account_type', $profile->account_type) === 'checking')>Checking</option>
                            <option value="savings" @selected(old('account_type', $profile->account_type) === 'savings')>Savings</option>
                        </select>
                        <x-input-error :messages="$errors->get('account_type')" class="mt-2 text-rose-300" />
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-white/10 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('member.dashboard') }}" class="cca-muted-button text-center">Back to Dashboard</a>
                    <button class="cca-button">{{ $profile->exists ? 'Save Bank Details' : 'Add Bank Details' }}</button>
                </div>
            </form>
        </section>
    </div>
</x-dashboard.shell>
