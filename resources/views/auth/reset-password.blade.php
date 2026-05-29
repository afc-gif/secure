<x-guest-layout>
    <div class="cca-panel rounded-lg p-6 sm:p-8">
        <h2 class="text-2xl font-black text-white">Reset password</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Create a new password for your CCA partner profile.</p>

        <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email" class="cca-label">Email</label>
                <input id="email" class="cca-input mt-2" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300" />
            </div>

            <div>
                <label for="password" class="cca-label">New Password</label>
                <input id="password" class="cca-input mt-2" type="password" name="password" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-300" />
            </div>

            <div>
                <label for="password_confirmation" class="cca-label">Confirm Password</label>
                <input id="password_confirmation" class="cca-input mt-2" type="password" name="password_confirmation" required autocomplete="new-password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-300" />
            </div>

            <button type="submit" class="cca-button w-full">Reset Secure Password</button>
        </form>
    </div>
</x-guest-layout>
