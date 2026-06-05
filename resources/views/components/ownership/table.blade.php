@props(['participations'])

<section class="cca-card overflow-hidden">
    <div class="border-b border-white/[0.07] bg-white/[0.015] px-4 py-4 sm:px-5">
        <h2 class="text-lg font-black text-white">Privilege Sync History</h2>
        <p class="mt-1 text-sm text-slate-500">Secure access records linked to your member profile.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-[42rem] divide-y divide-white/[0.07] text-left text-sm">
            <thead class="bg-[#0b0d10]/70 text-xs uppercase tracking-[0.12em] text-slate-500">
                <tr>
                    <th class="px-5 py-4">Batch</th>
                    <th class="px-5 py-4">Tier</th>
                    <th class="px-5 py-4">Status</th>
                    <th class="px-5 py-4">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/[0.07] text-slate-300">
                @forelse ($participations as $participation)
                    @php
                        $batchTitle = $participation->batch?->title;
                        $displayBatchTitle = Str::startsWith($batchTitle ?? '', 'Batch 3') ? 'Batch 3' : ($batchTitle ?? 'Batch 3');
                    @endphp
                    <tr class="transition hover:bg-white/[0.035]">
                        <td class="max-w-[14rem] px-5 py-4 font-semibold text-white">
                            {{ $displayBatchTitle }}
                        </td>
                        <td class="px-5 py-4">{{ $participation->accessToken?->ownership_tier ?? $participation->batch?->ownership_level ?? 'Batch 3 Synchronized Class' }}</td>
                        <td class="px-5 py-4"><x-ownership.status-badge :status="$participation->participation_status" /></td>
                        <td class="px-5 py-4">{{ $participation->joined_at?->format('F j, Y') ?? 'June 7, 2025' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-5 py-6 text-slate-500" colspan="4">No ownership cycle participation yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
