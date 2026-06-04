<x-onboarding.layout :step="$step" :completion="$completion">
    <x-onboarding.form-card
        eyebrow="Step 4"
        title="Review & Confirmation"
        description="Review your member identity, address registry, and cooperative profile before synchronization."
    >
        <div class="grid gap-5 lg:grid-cols-3">
            <section class="rounded-lg border border-white/10 bg-white/[0.04] p-5">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#d4af62]">Identity</p>
                <dl class="mt-5 space-y-4 text-sm">
                    <div><dt class="text-slate-500">Legal Name</dt><dd class="mt-1 font-semibold text-white">{{ $profile->full_legal_name }}</dd></div>
                    <div><dt class="text-slate-500">Phone</dt><dd class="mt-1 font-semibold text-white">{{ $profile->phone }}</dd></div>
                    <div><dt class="text-slate-500">Date of Birth</dt><dd class="mt-1 font-semibold text-white">{{ $profile->date_of_birth?->format('M j, Y') ?? 'Not provided' }}</dd></div>
                </dl>
            </section>

            <section class="rounded-lg border border-white/10 bg-white/[0.04] p-5">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#d4af62]">Address Registry</p>
                <dl class="mt-5 space-y-4 text-sm">
                    <div><dt class="text-slate-500">Country</dt><dd class="mt-1 font-semibold text-white">{{ $profile->country }}</dd></div>
                    <div><dt class="text-slate-500">State/City</dt><dd class="mt-1 font-semibold text-white">{{ $profile->state }}, {{ $profile->city }}</dd></div>
                    <div><dt class="text-slate-500">Residential Address</dt><dd class="mt-1 font-semibold text-white">{{ $profile->residential_address }}</dd></div>
                    <div><dt class="text-slate-500">Postal Code</dt><dd class="mt-1 font-semibold text-white">{{ $profile->postal_code }}</dd></div>
                    <div><dt class="text-slate-500">Cash App</dt><dd class="mt-1 font-semibold text-white">{{ $profile->cash_app_handle }}</dd></div>
                </dl>
            </section>

            <section class="rounded-lg border border-white/10 bg-white/[0.04] p-5">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#d4af62]">Cooperative Profile</p>
                <dl class="mt-5 space-y-4 text-sm">
                    <div><dt class="text-slate-500">Occupation</dt><dd class="mt-1 font-semibold text-white">{{ $profile->occupation }}</dd></div>
                    <div><dt class="text-slate-500">Interest Type</dt><dd class="mt-1 font-semibold text-white">{{ Str::of($profile->agricultural_interest_type)->replace('_', ' ')->title() }}</dd></div>
                    <div><dt class="text-slate-500">Ownership Reason</dt><dd class="mt-1 leading-6 text-slate-300">{{ $profile->ownership_interest_reason }}</dd></div>
                    <div><dt class="text-slate-500">About</dt><dd class="mt-1 leading-6 text-slate-300">{{ $profile->bio }}</dd></div>
                </dl>
            </section>
        </div>

        <form method="POST" action="{{ route('onboarding.complete') }}" class="mt-6 space-y-6" x-on:submit="loading = true; loadingText = 'Finalizing Member Profile...'">
            @csrf

            <label class="flex gap-3 rounded-lg border border-emerald-300/20 bg-emerald-300/10 p-4 text-sm text-emerald-100">
                <input type="checkbox" name="confirm_profile" value="1" class="mt-1 rounded border-white/20 bg-black/40 text-emerald-400 focus:ring-emerald-300" required>
                <span>I confirm that these details are accurate for my CCA cooperative ownership profile.</span>
            </label>
            <x-input-error :messages="$errors->get('confirm_profile')" class="text-rose-300" />

            <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('onboarding.step', 3) }}" class="cca-muted-button">Back</a>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <span x-show="loading" x-transition class="text-sm font-semibold text-emerald-200" x-text="loadingText"></span>
                    <button class="cca-button" type="submit" x-bind:disabled="loading">Synchronize Ownership Profile</button>
                </div>
            </div>
        </form>
    </x-onboarding.form-card>
</x-onboarding.layout>
