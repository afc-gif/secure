<x-dashboard.shell title="Secure Access Token" eyebrow="Ownership Activation">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-[#f35aa5]/25 bg-[#f35aa5]/10 px-5 py-4 text-sm font-semibold text-[#ffd4e9]">{{ session('status') }}</div>
    @endif

    <div class="grid gap-5 xl:grid-cols-[minmax(0,1fr)_minmax(0,1fr)]" x-data="{ loading: false, loadingText: 'Submitting Payment Confirmation...' }">
        <x-ownership.token-panel>
            @if ($dashboardUnlocked)
                <div class="rounded-lg border border-[#f35aa5]/20 bg-[#f35aa5]/10 p-4 sm:p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#ffd4e9]/70">VIP Token</p>
                    <h2 class="mt-2 text-xl font-black text-white">Dashboard access active</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-300">Your secure access token has already been validated for the active member dashboard.</p>
                </div>
            @else
                <div class="space-y-6">
                    <x-onboarding.secure-section
                        title="Dashboard Unlock Payment /"
                        description="Complete the active VIP payment and submit the transaction reference for admin confirmation."
                    />

                    @if ($paymentToken)
                        <div class="rounded-lg border border-[#d8bf7a]/25 bg-[#d8bf7a]/10 p-4 sm:p-5">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#fff0bf]/70">Unlock Price</p>
                                    <p class="mt-2 text-3xl font-black leading-tight text-white">{{ $paymentToken->price_currency }} {{ number_format((float) $paymentToken->price, 2) }}</p>
                                    <p class="mt-2 text-xs font-semibold uppercase tracking-[0.12em] text-[#fff0bf]/70">{{ $paymentToken->batch?->title }}</p>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#fff0bf]/70">BTC Wallet</p>
                                    <p class="mt-2 break-all rounded-md border border-white/10 bg-[#08090c]/70 px-3 py-3 font-mono text-sm leading-6 text-white">{{ $paymentToken->btc_wallet_address }}</p>
                                </div>
                            </div>
                        </div>

                        @if ($pendingPayment)
                            <div class="rounded-lg border border-[#f35aa5]/25 bg-[#f35aa5]/10 p-4 sm:p-5">
                                <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#ffd4e9]/70">Payment Pending</p>
                                <h2 class="mt-2 text-lg font-black text-white">Admin review in progress</h2>
                                <p class="mt-2 break-all font-mono text-sm text-slate-300">{{ $pendingPayment->payment_reference }}</p>
                            </div>
                        @else
                            <form method="POST" action="{{ route('member.access-token.payment.confirm') }}" class="space-y-5" x-on:submit="loading = true; loadingText = 'Submitting Payment Confirmation...'">
                                @csrf
                                <input type="hidden" name="payment_token_id" value="{{ $paymentToken->id }}">

                                <div>
                                    <label for="btc_transaction_reference" class="cca-label">BTC Transaction / Reference</label>
                                    <input id="btc_transaction_reference" name="btc_transaction_reference" value="{{ old('btc_transaction_reference') }}" class="cca-input mt-2 font-mono" placeholder="Transaction hash or payment reference">
                                    <x-input-error :messages="$errors->get('btc_transaction_reference')" class="mt-2 text-rose-300" />
                                </div>

                                <div>
                                    <label for="payment_notes" class="cca-label">Payment Notes <span class="text-slate-500">(optional)</span></label>
                                    <textarea id="payment_notes" name="payment_notes" rows="4" class="cca-input mt-2 resize-none" placeholder="Add any details admin should verify">{{ old('payment_notes') }}</textarea>
                                    <x-input-error :messages="$errors->get('payment_notes')" class="mt-2 text-rose-300" />
                                </div>

                                <div class="flex flex-col gap-3 border-t border-white/10 pt-5 sm:flex-row sm:items-center sm:justify-between">
                                    <span x-show="loading" x-transition class="text-sm font-semibold text-[#ffd4e9]" x-text="loadingText"></span>
                                    <button class="cca-button w-full sm:w-auto" x-bind:disabled="loading">Submit Payment</button>
                                </div>
                            </form>
                        @endif
                    @else
                        <div class="rounded-lg border border-[#d8bf7a]/25 bg-[#d8bf7a]/10 p-4 sm:p-5">
                            <p class="text-xs font-bold uppercase tracking-[0.14em] text-[#fff0bf]/70">Payment Setup Missing</p>
                            <h2 class="mt-2 text-lg font-black text-white">VIP payment is not available yet</h2>
                            <p class="mt-2 text-sm leading-6 text-slate-300">Admin must create one active VIP payment token with a price and BTC wallet address.</p>
                        </div>
                    @endif

                    <details class="rounded-lg border border-white/10 bg-white/[0.03] p-4">
                        <summary class="cursor-pointer text-xs font-black uppercase tracking-[0.14em] text-slate-300">Already have a VIP token?</summary>
                        <form method="POST" action="{{ route('member.access-token.store') }}" class="mt-5 space-y-5" x-on:submit="loading = true; loadingText = 'Synchronizing Dashboard Privilege...'">
                            @csrf
                            <div>
                                <label for="token" class="cca-label">Secure Access Token</label>
                                <input id="token" name="token" value="{{ old('token') }}" class="cca-input mt-2 font-mono uppercase" placeholder="VIPXXXXXXXXXX" required>
                                <x-input-error :messages="$errors->get('token')" class="mt-2 text-rose-300" />
                            </div>
                            <div class="flex flex-col gap-3 border-t border-white/10 pt-5 sm:flex-row sm:items-center sm:justify-between">
                                <span x-show="loading" x-transition class="text-sm font-semibold text-[#ffd4e9]" x-text="loadingText"></span>
                                <button class="cca-button w-full sm:w-auto" x-bind:disabled="loading">Authorize Entry</button>
                            </div>
                        </form>
                    </details>
                </div>
            @endif
        </x-ownership.token-panel>

        <x-ownership.table :participations="$participations" />
    </div>
</x-dashboard.shell>
