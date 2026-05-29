<x-dashboard.shell title="Contribution review" eyebrow="Admin Ledger">
    <section class="cca-card mx-auto max-w-4xl p-6 sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="font-mono text-xs font-bold text-[#d4af62]">{{ $contribution->payment_reference }}</p>
                <h2 class="mt-3 text-2xl font-black text-white">{{ $contribution->user->name }}</h2>
                <p class="mt-2 text-sm text-slate-400">{{ $contribution->getTypeLabel() }} · {{ $contribution->batch?->title ?? 'General cooperative pool' }}</p>
            </div>
            <x-ownership.status-badge :status="$contribution->status" />
        </div>

        <dl class="mt-8 grid gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-white/10 bg-white/[0.04] p-4"><dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Amount</dt><dd class="mt-2 text-xl font-black text-white">{{ $contribution->currency }} {{ number_format((float) $contribution->amount, 2) }}</dd></div>
            <div class="rounded-lg border border-white/10 bg-white/[0.04] p-4"><dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Member</dt><dd class="mt-2 text-sm font-bold text-white">{{ $contribution->user->email }}</dd></div>
            <div class="rounded-lg border border-white/10 bg-white/[0.04] p-4"><dt class="text-xs uppercase tracking-[0.18em] text-slate-500">Reviewed By</dt><dd class="mt-2 text-sm font-bold text-white">{{ $contribution->approvingAdmin?->name ?? 'Pending' }}</dd></div>
        </dl>

        <div class="mt-5 rounded-lg border border-white/10 bg-white/[0.04] p-4">
            <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Member notes</p>
            <p class="mt-2 text-sm leading-6 text-slate-300">{{ $contribution->notes ?? 'No member notes supplied.' }}</p>
        </div>

        @if ($contribution->isPending())
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <form method="POST" action="{{ route('admin.contributions.approve', $contribution) }}" class="rounded-lg border border-emerald-300/20 bg-emerald-300/10 p-4">
                    @csrf
                    @method('PATCH')
                    <label class="block">
                        <span class="text-sm font-bold text-emerald-100">Approval notes</span>
                        <textarea name="admin_notes" rows="3" class="mt-2 w-full rounded-lg border-white/10 bg-black/20 text-white"></textarea>
                    </label>
                    <button class="cca-button mt-4 w-full">Approve Contribution</button>
                </form>

                <form method="POST" action="{{ route('admin.contributions.reject', $contribution) }}" class="rounded-lg border border-rose-300/20 bg-rose-300/10 p-4">
                    @csrf
                    @method('PATCH')
                    <label class="block">
                        <span class="text-sm font-bold text-rose-100">Rejection reason</span>
                        <textarea name="admin_notes" rows="3" class="mt-2 w-full rounded-lg border-white/10 bg-black/20 text-white">Admin review</textarea>
                    </label>
                    <button class="cca-muted-button mt-4 w-full">Reject Contribution</button>
                </form>
            </div>
        @else
            <div class="mt-5 rounded-lg border border-white/10 bg-white/[0.04] p-4">
                <p class="text-xs uppercase tracking-[0.18em] text-slate-500">Admin notes</p>
                <p class="mt-2 text-sm leading-6 text-slate-300">{{ $contribution->admin_notes ?? 'No admin notes.' }}</p>
            </div>
        @endif
    </section>
</x-dashboard.shell>
