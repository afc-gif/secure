<?php

namespace App\Notifications;

use App\Models\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BatchParticipationConfirmedNotification extends Notification
{
    use Queueable;

    public function __construct(private Batch $batch)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Dashboard access active',
            'body' => "Your access to {$this->batch->title} is active.",
            'category' => 'dashboard_access',
            'tone' => 'success',
            'batch_id' => $this->batch->id,
            'action_label' => 'View Batches',
            'url' => route('member.batches.index'),
        ];
    }
}
