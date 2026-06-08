@props(['notifications', 'unreadCount' => 0])

<div class="relative">
    <details class="group">
        <summary class="relative flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-lg border border-[#d8bf7a]/15 bg-white/[0.04] text-[#d8bf7a] transition hover:border-[#d8bf7a]/35 hover:bg-[#d8bf7a]/10">
            <span class="sr-only">Notifications</span>
            <span class="text-lg">!</span>
            @if ($unreadCount > 0)
                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#d8bf7a] px-1 text-[10px] font-black text-[#08090b]">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        </summary>

        <div class="absolute right-0 z-20 mt-3 w-[min(22rem,calc(100vw-6rem))] rounded-lg border border-[#d8bf7a]/15 bg-[#0d0f13] p-3 shadow-2xl">
            <div class="flex items-center justify-between gap-3 px-2 pb-2">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">Notifications</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $unreadCount }} unread</p>
                </div>
                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}">
                        @csrf
                        <button class="rounded-md border border-white/10 bg-white/[0.04] px-2 py-1 text-[10px] font-black uppercase tracking-[0.12em] text-slate-300 transition hover:bg-white/[0.08]">Clear</button>
                    </form>
                @endif
            </div>

            <div class="max-h-[24rem] space-y-2 overflow-y-auto pr-1">
                @forelse ($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $tone = $notification->data['tone'] ?? ($isUnread ? 'gold' : 'slate');
                    @endphp

                    <div @class([
                        'rounded-lg border p-3 transition',
                        'border-[#d8bf7a]/25 bg-[#d8bf7a]/10' => $isUnread && $tone === 'gold',
                        'border-emerald-300/20 bg-emerald-300/10' => $isUnread && $tone === 'success',
                        'border-rose-300/20 bg-rose-300/10' => $isUnread && $tone === 'danger',
                        'border-white/10 bg-white/[0.035] hover:bg-white/[0.06]' => ! $isUnread || ! in_array($tone, ['gold', 'success', 'danger'], true),
                    ])>
                        <div class="flex gap-3">
                            <span @class([
                                'mt-1 h-2 w-2 shrink-0 rounded-full',
                                'bg-[#d8bf7a]' => $isUnread && $tone === 'gold',
                                'bg-emerald-300' => $isUnread && $tone === 'success',
                                'bg-rose-300' => $isUnread && $tone === 'danger',
                                'bg-slate-600' => ! $isUnread,
                            ])></span>
                            <a href="{{ route('notifications.open', $notification) }}" class="min-w-0 flex-1">
                                <p class="text-sm font-bold text-white">{{ $notification->data['title'] ?? 'Portal update' }}</p>
                                <p class="mt-1 text-xs leading-5 text-slate-400">{{ $notification->data['body'] ?? 'New account activity.' }}</p>
                                <div class="mt-2 flex flex-wrap items-center gap-2 text-[10px] font-bold uppercase tracking-[0.12em] text-slate-500">
                                    <span>{{ $notification->created_at?->diffForHumans() }}</span>
                                    @if (! empty($notification->data['reference']))
                                        <span class="max-w-full break-all font-mono">{{ $notification->data['reference'] }}</span>
                                    @endif
                                </div>
                            </a>
                        </div>

                        @if ($isUnread)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}" class="mt-2 flex justify-end">
                                @csrf
                                <button class="text-[10px] font-black uppercase tracking-[0.12em] text-slate-500 transition hover:text-slate-200">Mark read</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <div class="rounded-lg border border-white/10 bg-white/[0.04] p-3 text-sm text-slate-500">No notifications yet.</div>
                @endforelse
            </div>

            <div class="mt-3 border-t border-white/10 pt-3">
                <a href="{{ route('notifications.index') }}" class="cca-muted-button block text-center">Open Notification Center</a>
            </div>
        </div>
    </details>
</div>
