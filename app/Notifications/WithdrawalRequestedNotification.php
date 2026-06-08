<?php

namespace App\Notifications;

use App\Models\SettlementProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WithdrawalRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(private SettlementProfile $profile)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $memberName = $this->profile->user?->name ?? 'A member';

        return [
            'title' => 'Withdrawal requested',
            'body' => $memberName.' requested a withdrawal. Review the payout details from the admin withdrawal queue.',
            'category' => 'withdrawal_review',
            'tone' => 'gold',
            'amount' => $this->profile->withdrawal_amount,
            'currency' => $this->profile->currency ?? 'USD',
            'action_label' => 'Review Withdrawal',
            'url' => route('admin.withdrawals.index', ['status' => 'processing']),
        ];
    }
}
