<?php

namespace App\Notifications;

use App\Models\Contribution;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VipPaymentSubmittedNotification extends Notification
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
            'title' => 'VIP payment submitted',
            'body' => $this->contribution->user->name.' confirmed a BTC payment for VIP dashboard access.',
            'amount' => $this->contribution->amount,
            'currency' => $this->contribution->currency,
            'reference' => $this->contribution->payment_reference,
            'url' => route('admin.contributions.show', $this->contribution),
        ];
    }
}
