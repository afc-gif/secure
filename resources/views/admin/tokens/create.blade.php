<x-dashboard.shell title="Add VIP Payment Setup" eyebrow="Super Admin">
    <section class="cca-card overflow-hidden">
        <div class="border-b border-white/10 px-4 py-4 sm:px-7 sm:py-5">
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Member Unlock Payment</p>
            <h2 class="mt-2 text-xl font-black text-white sm:text-2xl">Set the price and Bitcoin wallet</h2>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">Locked members will see this price and wallet on their dashboard unlock page. Leave member assignment empty to make the setup available to all locked members in the selected batch.</p>
        </div>
        <form method="POST" action="{{ route('admin.tokens.store') }}" class="space-y-5 p-4 sm:space-y-6 sm:p-7">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
                <div class="min-w-0">
                    <label for="batch_id" class="cca-label">Batch</label>
                    <select id="batch_id" name="batch_id" class="cca-input mt-2" required>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}" @selected((int) old('batch_id', request('batch_id')) === $batch->id)>{{ $batch->title }} - {{ $batch->batch_code }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('batch_id')" class="mt-2 text-rose-300" />
                </div>
                <div class="min-w-0">
                    <label for="ownership_tier" class="cca-label">Access Level</label>
                    <input id="ownership_tier" name="ownership_tier" value="{{ old('ownership_tier', 'VIP Dashboard Access') }}" class="cca-input mt-2" required>
                </div>
                <div class="min-w-0">
                    <label for="price" class="cca-label">Unlock Price</label>
                    <input id="price" name="price" value="{{ old('price') }}" inputmode="decimal" class="cca-input mt-2" placeholder="250.00" required>
                    <x-input-error :messages="$errors->get('price')" class="mt-2 text-rose-300" />
                </div>
                <div class="min-w-0">
                    <label for="price_currency" class="cca-label">Price Currency</label>
                    <input id="price_currency" name="price_currency" value="{{ old('price_currency', 'USD') }}" class="cca-input mt-2 uppercase" readonly required>
                    <x-input-error :messages="$errors->get('price_currency')" class="mt-2 text-rose-300" />
                </div>
                <div class="min-w-0 sm:col-span-2">
                    <label for="btc_wallet_address" class="cca-label">Bitcoin Wallet Address</label>
                    <input id="btc_wallet_address" name="btc_wallet_address" value="{{ old('btc_wallet_address') }}" class="cca-input mt-2 font-mono" placeholder="bc1..." required>
                    <p class="mt-2 text-xs text-slate-500">Members will use this wallet as the Bitcoin payment destination.</p>
                    <x-input-error :messages="$errors->get('btc_wallet_address')" class="mt-2 text-rose-300" />
                </div>
                <div class="min-w-0">
                    <label for="assigned_to_user_id" class="cca-label">Assign to Member <span class="text-slate-500">(optional)</span></label>
                    <select id="assigned_to_user_id" name="assigned_to_user_id" class="cca-input mt-2">
                        <option value="">Available to all locked members</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}" @selected((int) old('assigned_to_user_id') === $member->id)>{{ $member->name }} - {{ $member->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-0">
                    <label for="quantity" class="cca-label">Quantity</label>
                    <input id="quantity" type="number" min="1" max="100" name="quantity" value="{{ old('quantity', 1) }}" class="cca-input mt-2">
                    <p class="mt-2 text-xs text-slate-500">Use 1 for a shared payment setup.</p>
                </div>
                <div class="min-w-0">
                    <label for="expires_at" class="cca-label">Expires At</label>
                    <input id="expires_at" type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="cca-input mt-2">
                </div>
            </div>
            <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.tokens.index') }}" class="cca-muted-button w-full sm:w-auto">Back</a>
                <button class="cca-button w-full sm:w-auto">Save Payment Setup</button>
            </div>
        </form>
    </section>
</x-dashboard.shell>
