<?php

namespace App\Notifications;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContributionRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(private Contribution $contribution, private string $reason)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Contribution rejected',
            'body' => $this->reason,
            'category' => 'payment',
            'tone' => 'danger',
            'amount' => $this->contribution->amount,
            'currency' => $this->contribution->currency,
            'reference' => $this->contribution->payment_reference,
            'action_label' => 'View Details',
            'url' => route('member.contributions.show', $this->contribution),
        ];
    }
}
