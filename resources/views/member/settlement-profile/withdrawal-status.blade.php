<x-dashboard.shell title="Withdrawal Status" eyebrow="Member Payouts">
    @php
        $isComplete = $profile->withdrawal_status === 'completed';
    @endphp

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-[#f35aa5]/25 bg-[#f35aa5]/10 px-5 py-4 text-sm font-semibold text-[#ffd4e9]">{{ session('success') }}</div>
    @endif

    <section class="mx-auto max-w-xl rounded-lg border border-white/[0.07] bg-[#101116]/95 p-6 text-center shadow-xl shadow-black/20 sm:p-8">
        @if ($isComplete)
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-emerald-300/25 bg-emerald-300/10 text-2xl font-black text-emerald-100">&check;</div>
            <h2 class="mt-6 text-2xl font-black text-white">Withdrawal Complete</h2>
            <p class="mt-3 text-sm leading-6 text-slate-400">Your withdrawal has been completed. Amount: USD {{ number_format((float) $profile->withdrawal_amount, 2) }}.</p>
        @else
            <div class="mx-auto h-16 w-16 animate-spin rounded-full border-4 border-white/10 border-t-[#f35aa5]"></div>
            <h2 class="mt-6 text-2xl font-black text-white">Processing</h2>
            <p class="mt-3 text-sm leading-6 text-slate-400">Your withdrawal will be complete within 24hrs. Amount: USD {{ number_format((float) $profile->withdrawal_amount, 2) }}.</p>
        @endif

        <dl class="mt-6 grid gap-3 rounded-lg border border-white/10 bg-white/[0.025] p-4 text-left sm:grid-cols-2">
            <div>
                <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Available Balance</dt>
                <dd class="mt-2 font-mono text-sm font-black text-white">USD {{ number_format($balance['available'], 2) }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-[0.12em] text-slate-500">Withdrawn</dt>
                <dd class="mt-2 font-mono text-sm font-black text-white">USD {{ number_format($balance['completed_withdrawals'], 2) }}</dd>
            </div>
        </dl>

        <div class="mt-7 flex flex-col gap-3 border-t border-white/10 pt-5 sm:flex-row sm:justify-center">
            <a href="{{ route('member.dashboard') }}" class="cca-button text-center">Back to Dashboard</a>
            <a href="{{ route('member.settlement-profile.show') }}" class="cca-muted-button text-center">Withdrawal Details</a>
        </div>
    </section>
</x-dashboard.shell>
