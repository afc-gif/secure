<x-dashboard.shell title="Edit Profile" eyebrow="Account Settings">
    <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <section class="space-y-4">
            <div class="cca-card p-4 sm:p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="cca-card p-4 sm:p-6">
                @include('profile.partials.update-password-form')
            </div>
        </section>

        <aside class="space-y-4">
            <div class="cca-card relative overflow-hidden p-5">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_15%_15%,rgba(217,54,173,0.16),transparent_16rem)]"></div>
                <div class="relative">
                    <p class="cca-kicker">Account</p>
                    <h2 class="mt-3 text-2xl font-black text-white">{{ auth()->user()->name }}</h2>
                    <p class="mt-2 break-all font-mono text-xs text-slate-500">{{ auth()->user()->reference_token }}</p>
                </div>
            </div>

            <div class="cca-card p-4 sm:p-5">
                @include('profile.partials.delete-user-form')
            </div>
        </aside>
    </div>
</x-dashboard.shell>
