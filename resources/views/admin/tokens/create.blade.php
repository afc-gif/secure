<x-dashboard.shell title="Generate Secure Access Tokens" eyebrow="Secure Token Registry">
    <section class="cca-card overflow-hidden">
        <div class="border-b border-white/10 px-4 py-4 sm:px-7 sm:py-5">
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-slate-500">Secure Access Token</p>
            <h2 class="mt-2 text-xl font-black text-white sm:text-2xl">Create VIP dashboard tokens</h2>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400">Generate one assigned token or bulk issue unassigned VIP tokens for dashboard privilege synchronization.</p>
        </div>
        <form method="POST" action="{{ route('admin.tokens.store') }}" class="space-y-5 p-4 sm:space-y-6 sm:p-7">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2 sm:gap-5">
                <div class="min-w-0">
                    <label for="batch_id" class="cca-label">Privilege Batch</label>
                    <select id="batch_id" name="batch_id" class="cca-input mt-2" required>
                        @foreach ($batches as $batch)
                            <option value="{{ $batch->id }}" @selected((int) old('batch_id', request('batch_id')) === $batch->id)>{{ $batch->title }} - {{ $batch->batch_code }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('batch_id')" class="mt-2 text-rose-300" />
                </div>
                <div class="min-w-0">
                    <label for="ownership_tier" class="cca-label">Privilege Tier</label>
                    <input id="ownership_tier" name="ownership_tier" value="{{ old('ownership_tier', 'Batch 3 Synchronized Class') }}" class="cca-input mt-2" required>
                </div>
                <div class="min-w-0">
                    <label for="assigned_to_user_id" class="cca-label">Assign to Member <span class="text-slate-500">(optional)</span></label>
                    <select id="assigned_to_user_id" name="assigned_to_user_id" class="cca-input mt-2">
                        <option value="">Unassigned VIP token pool</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}" @selected((int) old('assigned_to_user_id') === $member->id)>{{ $member->name }} - {{ $member->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="min-w-0">
                    <label for="quantity" class="cca-label">Quantity</label>
                    <input id="quantity" type="number" min="1" max="100" name="quantity" value="{{ old('quantity', 1) }}" class="cca-input mt-2">
                    <p class="mt-2 text-xs text-slate-500">Use 1 when assigning a VIP token to a specific member.</p>
                </div>
                <div class="min-w-0">
                    <label for="expires_at" class="cca-label">Expires At</label>
                    <input id="expires_at" type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="cca-input mt-2">
                </div>
            </div>
            <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.tokens.index') }}" class="cca-muted-button w-full sm:w-auto">Back</a>
                <button class="cca-button w-full sm:w-auto">Generate Secure Tokens</button>
            </div>
        </form>
    </section>
</x-dashboard.shell>
