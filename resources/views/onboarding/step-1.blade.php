<x-onboarding.layout :step="$step" :completion="$completion">
    <x-onboarding.form-card
        eyebrow="Step 1"
        title="Identity Verification"
        description="Register the legal identity that will be synchronized with your cooperative ownership profile."
    >
        <form method="POST" action="{{ route('onboarding.step.identity.store') }}" class="space-y-6" x-on:submit="loading = true; loadingText = 'Synchronizing Cooperative Identity...'">
            @csrf

            <x-onboarding.secure-section
                title="Protected Identity Intake"
                description="Your identity details are used to maintain a trusted member registry and verify cooperative ownership access."
            />

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="full_legal_name" class="cca-label">Full Legal Name</label>
                    <input id="full_legal_name" name="full_legal_name" value="{{ old('full_legal_name', $profile->full_legal_name) }}" class="cca-input mt-2" autocomplete="name" required>
                    <x-input-error :messages="$errors->get('full_legal_name')" class="mt-2 text-rose-300" />
                </div>

                <div>
                    <label for="phone" class="cca-label">Phone Number</label>
                    <input id="phone" name="phone" value="{{ old('phone', $profile->phone ?? auth()->user()->phone) }}" class="cca-input mt-2" autocomplete="tel" required>
                    <x-input-error :messages="$errors->get('phone')" class="mt-2 text-rose-300" />
                </div>

                <div>
                    <label for="date_of_birth" class="cca-label">Date of Birth <span class="text-slate-500">(optional)</span></label>
                    <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}" class="cca-input mt-2">
                    <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2 text-rose-300" />
                </div>

                <div>
                    <label for="gender" class="cca-label">Gender <span class="text-slate-500">(optional)</span></label>
                    <select id="gender" name="gender" class="cca-input mt-2">
                        <option value="">Select preference</option>
                        @foreach (['female' => 'Female', 'male' => 'Male', 'non_binary' => 'Non-binary', 'prefer_not_to_say' => 'Prefer not to say'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('gender', $profile->gender) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2 text-rose-300" />
                </div>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-end">
                <span x-show="loading" x-transition class="text-sm font-semibold text-emerald-200" x-text="loadingText"></span>
                <button class="cca-button" type="submit" x-bind:disabled="loading">Continue to Address Registry</button>
            </div>
        </form>
    </x-onboarding.form-card>
</x-onboarding.layout>
