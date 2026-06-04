<x-dashboard.shell title="Secure Access Token" eyebrow="Ownership Activation">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-[#f35aa5]/25 bg-[#f35aa5]/10 px-5 py-4 text-sm font-semibold text-[#ffd4e9]">{{ session('status') }}</div>
    @endif

    <div class="grid gap-5 xl:grid-cols-[0.95fr_1.05fr]" x-data="{ loading: false, loadingText: 'Validating Secure Access...' }">
        <x-ownership.token-panel>
            <div class="mb-6 rounded-lg border border-[#d8bf7a]/20 bg-[#d8bf7a]/10 p-4 sm:p-5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#fff0bf]/70">VIP Bitcoin Payment</p>
                        <h2 class="mt-2 text-xl font-black text-white">Payment details</h2>
                    </div>
                    @if ($paymentToken?->price)
                        <span class="rounded-md border border-[#d8bf7a]/25 bg-black/20 px-3 py-1.5 font-mono text-sm font-black text-[#fff0bf]">
                            {{ $paymentToken->price_currency }} {{ number_format((float) $paymentToken->price, 2) }}
                        </span>
                    @endif
                </div>

                @if ($paymentToken)
                    <dl class="mt-5 divide-y divide-white/10">
                        <div class="grid gap-1 py-3 first:pt-0">
                            <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">BTC Wallet</dt>
                            <dd class="break-all font-mono text-sm leading-6 text-slate-100">{{ $paymentToken->btc_wallet_address }}</dd>
                        </div>
                        <div class="grid gap-1 py-3">
                            <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">VIP Batch</dt>
                            <dd class="text-sm font-bold leading-6 text-slate-200">{{ $paymentToken->batch?->title ?? 'VIP dashboard access' }}</dd>
                        </div>
                        <div class="grid gap-1 py-3 last:pb-0">
                            <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Activation</dt>
                            <dd class="text-sm leading-6 text-slate-300">Pay the BTC wallet above, wait for admin approval, then enter the issued VIP token below.</dd>
                        </div>
                    </dl>
                @else
                    <p class="mt-4 text-sm leading-6 text-slate-300">VIP payment details are not available yet. An admin must set the token price and BTC wallet before members can pay.</p>
                @endif
            </div>

            <form method="POST" action="{{ route('member.access-token.store') }}" class="space-y-6" x-on:submit="loading = true; loadingText = 'Synchronizing Dashboard Privilege...'">
                @csrf
                <x-onboarding.secure-section
                    title="Token Validation Engine /"
                    description="Enter the VIP token issued after admin approval of your crypto payment. The system verifies credential validity, batch synchronization constraints, and unique contract matching before secure access is granted."
                />
                <div>
                    <label for="token" class="cca-label">Secure Access Token</label>
                    <input id="token" name="token" value="{{ old('token') }}" class="cca-input mt-2 font-mono uppercase" placeholder="VIPXXXXXXXXXX" required>
                    <x-input-error :messages="$errors->get('token')" class="mt-2 text-rose-300" />
                </div>
                <div class="flex flex-col gap-3 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <span x-show="loading" x-transition class="text-sm font-semibold text-[#ffd4e9]" x-text="loadingText"></span>
                    <button class="cca-button" x-bind:disabled="loading">AUTHORIZE SECURE ENTRY</button>
                </div>
            </form>
        </x-ownership.token-panel>

        <x-ownership.table :participations="$participations" />
    </div>
</x-dashboard.shell>
