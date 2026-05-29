<x-onboarding.layout :step="$step" :completion="$completion">
    <x-onboarding.form-card
        eyebrow="Step 3"
        title="Cooperative Profile"
        description="Tell CCA how your professional background and agricultural interests align with countryside co-ownership."
    >
        <form method="POST" action="{{ route('onboarding.step.cooperative.store') }}" class="space-y-6" x-on:submit="loading = true; loadingText = 'Preparing Cooperative Profile...'">
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="occupation" class="cca-label">Occupation</label>
                    <input id="occupation" name="occupation" value="{{ old('occupation', $profile->occupation) }}" class="cca-input mt-2" required>
                    <x-input-error :messages="$errors->get('occupation')" class="mt-2 text-rose-300" />
                </div>

                <div>
                    <label for="agricultural_interest_type" class="cca-label">Agricultural Interest Type</label>
                    <select id="agricultural_interest_type" name="agricultural_interest_type" class="cca-input mt-2" required>
                        <option value="">Select interest</option>
                        @foreach (['crop_cycles' => 'Crop cycles', 'livestock' => 'Livestock', 'orchards' => 'Orchards', 'mixed_farming' => 'Mixed farming', 'agri_finance' => 'Agri-finance'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('agricultural_interest_type', $profile->agricultural_interest_type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('agricultural_interest_type')" class="mt-2 text-rose-300" />
                </div>

                <div class="sm:col-span-2">
                    <label for="ownership_interest_reason" class="cca-label">Ownership Interest Reason</label>
                    <textarea id="ownership_interest_reason" name="ownership_interest_reason" rows="4" class="cca-input mt-2" required>{{ old('ownership_interest_reason', $profile->ownership_interest_reason) }}</textarea>
                    <x-input-error :messages="$errors->get('ownership_interest_reason')" class="mt-2 text-rose-300" />
                </div>

                <div class="sm:col-span-2">
                    <label for="bio" class="cca-label">Short Bio/About</label>
                    <textarea id="bio" name="bio" rows="4" class="cca-input mt-2" required>{{ old('bio', $profile->bio) }}</textarea>
                    <x-input-error :messages="$errors->get('bio')" class="mt-2 text-rose-300" />
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('onboarding.step', 2) }}" class="cca-muted-button">Back</a>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <span x-show="loading" x-transition class="text-sm font-semibold text-emerald-200" x-text="loadingText"></span>
                    <button class="cca-button" type="submit" x-bind:disabled="loading">Review Ownership Profile</button>
                </div>
            </div>
        </form>
    </x-onboarding.form-card>
</x-onboarding.layout>
