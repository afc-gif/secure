<?php

namespace App\Notifications;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContributionApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(private Contribution $contribution)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Contribution approved',
            'body' => 'Your cooperative contribution has been confirmed.',
            'amount' => $this->contribution->amount,
            'currency' => $this->contribution->currency,
            'reference' => $this->contribution->payment_reference,
            'url' => route('member.contributions.show', $this->contribution),
        ];
    }
}
