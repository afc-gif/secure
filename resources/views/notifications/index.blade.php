<x-dashboard.shell title="Notification Center" eyebrow="Account Activity">
    @if (session('status'))
        <div class="mb-5 rounded-lg border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-sm font-semibold text-emerald-100">{{ session('status') }}</div>
    @endif

    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm leading-6 text-slate-400">{{ $unreadCount }} unread notification{{ $unreadCount === 1 ? '' : 's' }}</p>
        </div>
        @if ($unreadCount > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button class="cca-button w-full sm:w-auto">Mark All Read</button>
            </form>
        @endif
    </div>

    <section class="rounded-lg border border-white/[0.07] bg-[#0b0d10] shadow-xl shadow-black/20">
        <div class="divide-y divide-white/10">
            @forelse ($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $tone = $notification->data['tone'] ?? ($isUnread ? 'gold' : 'slate');
                @endphp

                <article @class([
                    'grid gap-4 p-4 sm:p-5 lg:grid-cols-[1fr_auto] lg:items-center',
                    'bg-[#d8bf7a]/5' => $isUnread && $tone === 'gold',
                    'bg-emerald-300/5' => $isUnread && $tone === 'success',
                    'bg-rose-300/5' => $isUnread && $tone === 'danger',
                ])>
                    <a href="{{ route('notifications.open', $notification) }}" class="min-w-0">
                        <div class="flex min-w-0 items-start gap-3">
                            <span @class([
                                'mt-2 h-2 w-2 shrink-0 rounded-full',
                                'bg-[#d8bf7a]' => $isUnread && $tone === 'gold',
                                'bg-emerald-300' => $isUnread && $tone === 'success',
                                'bg-rose-300' => $isUnread && $tone === 'danger',
                                'bg-slate-600' => ! $isUnread,
                            ])></span>
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-sm font-black text-white">{{ $notification->data['title'] ?? 'Portal update' }}</h2>
                                    @if ($isUnread)
                                        <span class="rounded-md border border-[#d8bf7a]/20 bg-[#d8bf7a]/10 px-2 py-0.5 text-[10px] font-black uppercase tracking-[0.12em] text-[#fff0bf]">Unread</span>
                                    @endif
                                </div>
                                <p class="mt-2 text-sm leading-6 text-slate-400">{{ $notification->data['body'] ?? 'New account activity.' }}</p>
                                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                    <span>{{ $notification->created_at?->diffForHumans() }}</span>
                                    @if (! empty($notification->data['reference']))
                                        <span class="max-w-full break-all font-mono">{{ $notification->data['reference'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>

                    <div class="flex flex-col gap-2 sm:flex-row lg:justify-end">
                        <a href="{{ route('notifications.open', $notification) }}" class="cca-muted-button text-center">{{ $notification->data['action_label'] ?? 'Open' }}</a>
                        @if ($isUnread)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                <button class="cca-muted-button w-full">Mark Read</button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="p-6 text-sm text-slate-500">No notifications yet.</div>
            @endforelse
        </div>
    </section>

    <div class="mt-6">{{ $notifications->links() }}</div>
</x-dashboard.shell>
