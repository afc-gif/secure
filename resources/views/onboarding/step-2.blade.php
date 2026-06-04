<x-onboarding.layout :step="$step" :completion="$completion">
    <x-onboarding.form-card
        eyebrow="Step 2"
        title="Address Registry"
        description="Secure the residential address used for member records, correspondence, and future account verification."
    >
        <form method="POST" action="{{ route('onboarding.step.address.store') }}" class="space-y-6" x-on:submit="loading = true; loadingText = 'Securing Ownership Registry...'">
            @csrf

            <x-onboarding.secure-section
                title="Institutional Address Registry"
                description="This registry helps CCA maintain clean ownership records for member communications and cooperative administration."
            />

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="country" class="cca-label">Country</label>
                    <input id="country" name="country" value="{{ old('country', $profile->country) }}" class="cca-input mt-2" autocomplete="country-name" required>
                    <x-input-error :messages="$errors->get('country')" class="mt-2 text-rose-300" />
                </div>

                <div>
                    <label for="state" class="cca-label">State</label>
                    <input id="state" name="state" value="{{ old('state', $profile->state) }}" class="cca-input mt-2" autocomplete="address-level1" required>
                    <x-input-error :messages="$errors->get('state')" class="mt-2 text-rose-300" />
                </div>

                <div>
                    <label for="city" class="cca-label">City</label>
                    <input id="city" name="city" value="{{ old('city', $profile->city) }}" class="cca-input mt-2" autocomplete="address-level2" required>
                    <x-input-error :messages="$errors->get('city')" class="mt-2 text-rose-300" />
                </div>

                <div>
                    <label for="postal_code" class="cca-label">Postal Code</label>
                    <input id="postal_code" name="postal_code" value="{{ old('postal_code', $profile->postal_code) }}" class="cca-input mt-2" autocomplete="postal-code" required>
                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2 text-rose-300" />
                </div>

                <div class="sm:col-span-2">
                    <label for="residential_address" class="cca-label">Residential Address</label>
                    <textarea id="residential_address" name="residential_address" rows="4" class="cca-input mt-2" autocomplete="street-address" required>{{ old('residential_address', $profile->residential_address) }}</textarea>
                    <x-input-error :messages="$errors->get('residential_address')" class="mt-2 text-rose-300" />
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('onboarding.step', 1) }}" class="cca-muted-button">Back</a>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <span x-show="loading" x-transition class="text-sm font-semibold text-emerald-200" x-text="loadingText"></span>
                    <button class="cca-button" type="submit" x-bind:disabled="loading">Continue to Cooperative Profile</button>
                </div>
            </div>
        </form>
    </x-onboarding.form-card>
</x-onboarding.layout>
