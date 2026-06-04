<x-guest-layout>
    <div class="cca-panel rounded-lg p-6 sm:p-8">
        <div class="mb-8 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <x-application-logo class="h-10 w-10" />
                <span class="font-bold tracking-[0.22em] text-slate-100">CCA</span>
            </a>
            <span class="rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-xs font-semibold text-emerald-200">Secure Portal</span>
        </div>

        <h2 class="text-2xl font-black text-white">Login</h2>
        <p class="mt-2 text-sm leading-6 text-slate-400">Enter vetted credentials to synchronize with the cooperative ledger.</p>

        <x-auth-session-status class="mt-5 text-sm text-emerald-300" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5" x-data="{ loading: false, message: 'Login', states: ['Synchronizing Ownership Records...', 'Verifying Partner Credentials...', 'Loading Cooperative Ledger...'] }" x-on:submit="loading = true; let i = 0; message = states[i]; setInterval(() => { i = (i + 1) % states.length; message = states[i]; }, 900)">
            @csrf

            <div>
                <label for="email" class="cca-label">Vetted Partner Email</label>
                <input id="email" class="cca-input mt-2" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="partner@countrycultureacres.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300" />
            </div>

            <div>
                <label for="reference_token" class="cca-label">Reference Token</label>
                <input id="reference_token" class="cca-input mt-2 uppercase" type="text" name="reference_token" value="{{ old('reference_token') }}" autocomplete="one-time-code" placeholder="Required for member accounts">
                <x-input-error :messages="$errors->get('reference_token')" class="mt-2 text-sm text-red-300" />
            </div>

            <div>
                <label for="password" class="cca-label">Password</label>
                <input id="password" class="cca-input mt-2" type="password" name="password" required autocomplete="current-password" placeholder="••••••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-300" />
            </div>

            <div class="flex items-center justify-between gap-4">
                <label class="inline-flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="remember" class="rounded border-white/10 bg-black/30 text-emerald-400 focus:ring-emerald-300">
                    Remember device
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-[#d4af62] hover:text-emerald-200" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="cca-button w-full" x-bind:disabled="loading">
                <span x-show="loading" class="mr-3 h-4 w-4 animate-spin rounded-full border-2 border-[#08100c]/30 border-t-[#08100c]"></span>
                <span x-text="message">Login</span>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-400">
            New cooperative partner?
            <a href="{{ route('register') }}" class="font-semibold text-emerald-300 hover:text-[#d4af62]">Signup</a>
        </p>
    </div>
</x-guest-layout>
