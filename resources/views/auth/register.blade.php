<x-guest-layout>
    <div class="cca-panel rounded-lg p-6 sm:p-8">
        <div class="mb-8 flex items-center gap-3">
            <x-application-logo class="h-10 w-10" />
            <div>
                <p class="text-xs uppercase tracking-[0.28em] text-emerald-300">Country Culture Acres</p>
                <h2 class="text-2xl font-black text-white">Member onboarding</h2>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="cca-label">Full Name</label>
                <input id="name" class="cca-input mt-2" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-300" />
            </div>

            <div>
                <label for="email" class="cca-label">Email</label>
                <input id="email" class="cca-input mt-2" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300" />
            </div>

            <div>
                <label for="phone" class="cca-label">Phone <span class="text-slate-500">(optional)</span></label>
                <input id="phone" class="cca-input mt-2" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel">
                <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-300" />
            </div>

            <div>
                <label for="password" class="cca-label">Password</label>
                <input id="password" class="cca-input mt-2" type="password" name="password" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-300" />
            </div>

            <div>
                <label for="password_confirmation" class="cca-label">Confirm Password</label>
                <input id="password_confirmation" class="cca-input mt-2" type="password" name="password_confirmation" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-300" />
            </div>

            <button type="submit" class="cca-button w-full">Create Member Profile</button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-400">
            Already onboarded?
            <a href="{{ route('login') }}" class="font-semibold text-emerald-300 hover:text-[#d4af62]">Secure access</a>
        </p>
    </div>
</x-guest-layout>
