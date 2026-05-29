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
            'title' => 'Harvest Cycle confirmed',
            'body' => "Your participation in {$this->batch->title} is active.",
            'batch_id' => $this->batch->id,
            'url' => route('member.batches.index'),
        ];
    }
}
