<x-dashboard.shell title="Contribution detail" eyebrow="Ownership Ledger">
    <section class="cca-card mx-auto max-w-3xl p-6 sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="font-mono text-xs font-bold text-[#d4af62]">{{ $contribution->payment_reference }}</p>
                <h2 class="mt-3 text-2xl font-black text-white">{{ $contribution->getTypeLabel() }}</h2>
                <p class="mt-2 text-sm text-slate-400">{{ $contribution->batch?->title ?? 'General cooperative pool' }}</p>
            </div>
            <x-ownership.status-badge :status="$contribution->status" />
        </div>

        <dl class="mt-8 grid gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10]/70 p-4"><dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Amount</dt><dd class="mt-2 text-xl font-black text-white">{{ $contribution->currency }} {{ number_format((float) $contribution->amount, 2) }}</dd></div>
            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10]/70 p-4"><dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Submitted</dt><dd class="mt-2 text-xl font-black text-white">{{ $contribution->created_at->format('M d, Y') }}</dd></div>
            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10]/70 p-4"><dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Reviewed</dt><dd class="mt-2 text-xl font-black text-white">{{ $contribution->approved_at?->format('M d, Y') ?? 'Pending' }}</dd></div>
            <div class="rounded-lg border border-white/[0.07] bg-[#0b0d10]/70 p-4"><dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Admin Notes</dt><dd class="mt-2 text-sm leading-6 text-slate-300">{{ $contribution->admin_notes ?? 'No admin notes yet.' }}</dd></div>
        </dl>

        @if ($contribution->notes)
            <div class="mt-5 rounded-lg border border-white/[0.07] bg-[#0b0d10]/70 p-4">
                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Member Notes</p>
                <p class="mt-2 text-sm leading-6 text-slate-300">{{ $contribution->notes }}</p>
            </div>
        @endif
    </section>
</x-dashboard.shell>
