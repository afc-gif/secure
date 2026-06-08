<?php

namespace App\Notifications;

use App\Models\Settlement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SettlementProcessedNotification extends Notification
{
    use Queueable;

    public function __construct(private Settlement $settlement)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Settlement processed',
            'body' => 'Your cooperative settlement has been processed.',
            'category' => 'settlement',
            'tone' => 'success',
            'amount' => $this->settlement->amount,
            'reference' => $this->settlement->reference_number,
            'action_label' => 'Open Dashboard',
            'url' => route('member.dashboard'),
        ];
    }
}
