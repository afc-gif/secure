<section>
    <header>
        <p class="cca-kicker">Identity</p>
        <h2 class="mt-2 text-lg font-black text-white">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm leading-6 text-slate-500">
            {{ __("Update your account, contact, address, and Cash App payout details.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <x-input-label for="name" :value="__('Account Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('email')" />
            </div>

            @if ($user->isMember())
                <div>
                    <x-input-label for="full_legal_name" :value="__('Full Legal Name')" />
                    <x-text-input id="full_legal_name" name="full_legal_name" type="text" class="mt-1 block w-full" :value="old('full_legal_name', $memberProfile?->full_legal_name)" autocomplete="name" />
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('full_legal_name')" />
                </div>

                <div>
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $memberProfile?->phone ?? $user->phone)" autocomplete="tel" />
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('phone')" />
                </div>

                <div>
                    <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
                    <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" :value="old('date_of_birth', $memberProfile?->date_of_birth?->format('Y-m-d'))" />
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('date_of_birth')" />
                </div>

                <div>
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select id="gender" name="gender" class="cca-input mt-1 block w-full">
                        @foreach (['' => 'Select gender', 'male' => 'Male', 'female' => 'Female', 'prefer_not_to_say' => 'Prefer not to say'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('gender', $memberProfile?->gender) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('gender')" />
                </div>

                <div>
                    <x-input-label for="country" :value="__('Country')" />
                    <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $memberProfile?->country)" autocomplete="country-name" />
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('country')" />
                </div>

                <div>
                    <x-input-label for="state" :value="__('State')" />
                    <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $memberProfile?->state)" autocomplete="address-level1" />
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('state')" />
                </div>

                <div>
                    <x-input-label for="city" :value="__('City')" />
                    <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $memberProfile?->city)" autocomplete="address-level2" />
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('city')" />
                </div>

                <div>
                    <x-input-label for="postal_code" :value="__('Postal Code')" />
                    <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" :value="old('postal_code', $memberProfile?->postal_code)" autocomplete="postal-code" />
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('postal_code')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="residential_address" :value="__('Residential Address')" />
                    <textarea id="residential_address" name="residential_address" rows="3" class="cca-input mt-1 block w-full" autocomplete="street-address">{{ old('residential_address', $memberProfile?->residential_address) }}</textarea>
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('residential_address')" />
                </div>

                <div>
                    <x-input-label for="cash_app_handle" :value="__('Cash App Handle')" />
                    <x-text-input id="cash_app_handle" name="cash_app_handle" type="text" class="mt-1 block w-full font-mono" :value="old('cash_app_handle', $memberProfile?->cash_app_handle)" placeholder="$YourHandle" autocomplete="off" />
                    <p class="mt-2 text-xs text-slate-500">Cash App is the only payout platform for members.</p>
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('cash_app_handle')" />
                </div>

                <div>
                    <x-input-label for="occupation" :value="__('Occupation')" />
                    <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full" :value="old('occupation', $memberProfile?->occupation)" />
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('occupation')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="agricultural_interest_type" :value="__('Interest Type')" />
                    <select id="agricultural_interest_type" name="agricultural_interest_type" class="cca-input mt-1 block w-full">
                        @foreach (['' => 'Select interest type', 'crop_cycles' => 'Crop Cycles', 'livestock' => 'Livestock', 'greenhouse' => 'Greenhouse', 'irrigation' => 'Irrigation', 'prefer_not_to_say' => 'I prefer not to say'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('agricultural_interest_type', $memberProfile?->agricultural_interest_type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('agricultural_interest_type')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="ownership_interest_reason" :value="__('Ownership Reason')" />
                    <textarea id="ownership_interest_reason" name="ownership_interest_reason" rows="3" class="cca-input mt-1 block w-full">{{ old('ownership_interest_reason', $memberProfile?->ownership_interest_reason) }}</textarea>
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('ownership_interest_reason')" />
                </div>

                <div class="sm:col-span-2">
                    <x-input-label for="bio" :value="__('About')" />
                    <textarea id="bio" name="bio" rows="3" class="cca-input mt-1 block w-full">{{ old('bio', $memberProfile?->bio) }}</textarea>
                    <x-input-error class="mt-2 text-rose-300" :messages="$errors->get('bio')" />
                </div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="sm:col-span-2">
                    <p class="mt-2 text-sm text-slate-400">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="rounded-md text-sm text-[#ffd4e9] underline hover:text-white focus:outline-none focus:ring-2 focus:ring-[#f35aa5] focus:ring-offset-2 focus:ring-offset-[#08090b]">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-[#ffd4e9]">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
