<x-guest-layout>
    <div class="cca-panel rounded-lg p-6 sm:p-8">
        <h2 class="text-2xl font-black text-white">Recover access</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Submit your partner email and we will send a secure password reset link.</p>

        <x-auth-session-status class="mt-5 text-sm text-emerald-300" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-5">
            @csrf

            <div>
                <label for="email" class="cca-label">Partner Email</label>
                <input id="email" class="cca-input mt-2" type="email" name="email" value="{{ old('email') }}" required autofocus>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300" />
            </div>

            <button type="submit" class="cca-button w-full">Send Reset Link</button>
        </form>
    </div>
</x-guest-layout>
