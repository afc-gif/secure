<x-dashboard.shell title="Contribution history" eyebrow="Cooperative Archive">
    <section class="cca-card overflow-hidden">
        <div class="border-b border-white/10 px-5 py-4">
            <h2 class="text-lg font-black text-white">Historical contribution requests</h2>
            <p class="mt-1 text-sm text-slate-500">Full audit trail for your cooperative capital activity.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-white/10 text-left text-sm">
                <thead class="bg-white/[0.03] text-xs uppercase tracking-[0.18em] text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Date</th>
                        <th class="px-5 py-4">Reference</th>
                        <th class="px-5 py-4">Amount</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-slate-300">
                    @forelse ($contributions as $contribution)
                        <tr class="transition hover:bg-white/[0.04]">
                            <td class="px-5 py-4">{{ $contribution->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4 font-mono text-xs text-[#d4af62]">{{ $contribution->payment_reference }}</td>
                            <td class="px-5 py-4">{{ $contribution->currency }} {{ number_format((float) $contribution->amount, 2) }}</td>
                            <td class="px-5 py-4"><x-ownership.status-badge :status="$contribution->status" /></td>
                            <td class="px-5 py-4">{{ $contribution->getTypeLabel() }}</td>
                        </tr>
                    @empty
                        <tr><td class="px-5 py-6 text-slate-500" colspan="5">No contribution history yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-white/10 px-5 py-4">{{ $contributions->links() }}</div>
    </section>
</x-dashboard.shell>
