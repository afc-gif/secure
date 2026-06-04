<?php

namespace App\Notifications;

use App\Models\Contribution;
use App\Models\AccessToken;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ContributionApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(private Contribution $contribution, private ?AccessToken $accessToken = null)
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
            'body' => $this->accessToken
                ? 'Your crypto payment was approved. Enter your VIP token to unlock the full dashboard.'
                : 'Your cooperative contribution has been confirmed.',
            'amount' => $this->contribution->amount,
            'currency' => $this->contribution->currency,
            'reference' => $this->contribution->payment_reference,
            'access_token' => $this->accessToken?->token,
            'url' => $this->accessToken
                ? route('member.access-token.create')
                : route('member.contributions.show', $this->contribution),
        ];
    }
}
