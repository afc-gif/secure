<x-dashboard.shell title="Submit contribution" eyebrow="Ownership Acre">
    <form method="POST" action="{{ route('member.contributions.store') }}" class="cca-card mx-auto max-w-3xl p-6 sm:p-8">
        @csrf
        <div>
            <p class="cca-kicker">Cooperative capital request</p>
            <h2 class="mt-3 text-2xl font-black text-white">Record a new contribution</h2>
            <p class="mt-2 text-sm leading-6 text-slate-400">Submitted requests enter admin review before they affect ownership percentage and settlement readiness.</p>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2">
            <label class="block">
                <span class="text-sm font-bold text-slate-300">Contribution type</span>
                <select name="contribution_type" class="cca-input mt-2">
                    @foreach ($types as $type)
                        <option class="bg-[#0b1110]" value="{{ $type }}" @selected(old('contribution_type') === $type)>{{ Str::of($type)->replace('_', ' ')->title() }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('contribution_type')" class="mt-2" />
            </label>

            <label class="block">
                <span class="text-sm font-bold text-slate-300">Harvest Cycle</span>
                <select name="batch_id" class="cca-input mt-2">
                    <option class="bg-[#0b1110]" value="">General cooperative pool</option>
                    @foreach ($batches as $batch)
                        <option class="bg-[#0b1110]" value="{{ $batch->id }}" @selected((string) old('batch_id') === (string) $batch->id)>{{ $batch->title }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('batch_id')" class="mt-2" />
            </label>

            <label class="block">
                <span class="text-sm font-bold text-slate-300">Amount (USD)</span>
                <input name="amount" value="{{ old('amount') }}" inputmode="decimal" class="cca-input mt-2" placeholder="2500.00">
                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
            </label>

            <label class="block">
                <span class="text-sm font-bold text-slate-300">Currency</span>
                <input name="currency" value="USD" readonly class="cca-input mt-2 uppercase">
                <x-input-error :messages="$errors->get('currency')" class="mt-2" />
            </label>
        </div>

        <label class="mt-5 block">
            <span class="text-sm font-bold text-slate-300">Notes</span>
            <textarea name="notes" rows="4" class="cca-input mt-2" placeholder="Optional cooperative context">{{ old('notes') }}</textarea>
            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
        </label>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('member.contributions.index') }}" class="cca-muted-button text-center">Cancel</a>
            <button class="cca-button">Submit Request</button>
        </div>
    </form>
</x-dashboard.shell>
