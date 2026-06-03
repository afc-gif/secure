@props(['participations'])

<section class="cca-card overflow-hidden">
    <div class="border-b border-white/[0.07] bg-white/[0.015] px-4 py-4 sm:px-5">
        <h2 class="text-lg font-black text-white">Participation History</h2>
        <p class="mt-1 text-sm text-slate-500">Batch access records linked to your cooperative profile.</p>
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
                    <tr class="transition hover:bg-white/[0.035]">
                        <td class="max-w-[14rem] px-5 py-4 font-semibold text-white">{{ $participation->batch->title }}</td>
                        <td class="px-5 py-4">{{ Str::of($participation->accessToken->ownership_tier)->replace('_', ' ')->title() }}</td>
                        <td class="px-5 py-4"><x-ownership.status-badge :status="$participation->participation_status" /></td>
                        <td class="px-5 py-4">{{ $participation->joined_at?->format('M j, Y') }}</td>
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
