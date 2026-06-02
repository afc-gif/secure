@props(['notifications'])

<div class="relative">
    <details class="group">
        <summary class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-lg border border-[#d8bf7a]/15 bg-white/[0.04] text-[#d8bf7a] transition hover:border-[#d8bf7a]/35 hover:bg-[#d8bf7a]/10">
            <span class="sr-only">Notifications</span>
            <span class="text-lg">!</span>
            @if ($notifications->count())
                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#d8bf7a] px-1 text-[10px] font-black text-[#08090b]">{{ $notifications->count() }}</span>
            @endif
        </summary>
        <div class="absolute right-0 z-20 mt-3 w-72 rounded-lg border border-[#d8bf7a]/15 bg-[#0d0f13] p-3 shadow-2xl">
            <p class="px-2 pb-2 text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Cooperative Alerts</p>
            <div class="space-y-2">
                @forelse ($notifications as $notification)
                    <a href="{{ $notification->data['url'] ?? route('dashboard') }}" class="block rounded-lg border border-white/10 bg-white/[0.04] p-3 hover:bg-white/[0.07]">
                        <p class="text-sm font-bold text-white">{{ $notification->data['title'] ?? 'Portal update' }}</p>
                        <p class="mt-1 text-xs leading-5 text-slate-400">{{ $notification->data['body'] ?? 'New account activity.' }}</p>
                    </a>
                @empty
                    <div class="rounded-lg border border-white/10 bg-white/[0.04] p-3 text-sm text-slate-500">No unread alerts.</div>
                @endforelse
            </div>
        </div>
    </details>
</div>
