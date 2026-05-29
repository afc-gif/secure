<x-dashboard.shell title="Cooperative access token" eyebrow="Ownership Activation">
    @if (session('status'))
        <div class="mb-6 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
    @endif

    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]" x-data="{ loading: false, loadingText: 'Validating Cooperative Access...' }">
        <x-ownership.token-panel>
            <form method="POST" action="{{ route('member.access-token.store') }}" class="space-y-6" x-on:submit="loading = true; loadingText = 'Synchronizing Ownership Cycle...'">
                @csrf
                <x-onboarding.secure-section
                    title="Token Validation Engine"
                    description="CCA validates token state, batch availability, expiration, revocation, and duplicate participation before access is activated."
                />
                <div>
                    <label for="token" class="cca-label">Cooperative Access Token</label>
                    <input id="token" name="token" value="{{ old('token') }}" class="cca-input mt-2 font-mono uppercase" placeholder="CCA-ACCESS-XXXXXXXXXXXX" required>
                    <x-input-error :messages="$errors->get('token')" class="mt-2 text-rose-300" />
                </div>
                <div class="flex flex-col gap-3 border-t border-white/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <span x-show="loading" x-transition class="text-sm font-semibold text-emerald-200" x-text="loadingText"></span>
                    <button class="cca-button" x-bind:disabled="loading">Unlock Ownership Access</button>
                </div>
            </form>
        </x-ownership.token-panel>

        <x-ownership.table :participations="$participations" />
    </div>
</x-dashboard.shell>
