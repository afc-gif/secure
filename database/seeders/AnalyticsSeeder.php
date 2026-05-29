<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Contribution;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

class AnalyticsSeeder extends Seeder
{
    public function run(): void
    {
        $members = User::where('role', 'member')->with(['contributions', 'batchMembers'])->get();

        foreach ($members as $member) {
            $this->log($member, 'onboarding_completed', 'Member onboarding completed for development demo.', ['status' => 'completed']);

            foreach ($member->batchMembers as $participation) {
                $this->log($member, 'batch_joined', 'Member joined '.$participation->batch->title.'.', ['batch_id' => $participation->batch_id]);
                $this->notify($member, 'Harvest Cycle confirmed', 'Your participation in '.$participation->batch->title.' is active.', route('member.batches.index'));
            }

            foreach ($member->contributions as $contribution) {
                $this->log($member, 'contribution_submitted', 'Contribution '.$contribution->payment_reference.' submitted.', ['contribution_id' => $contribution->id]);

                if ($contribution->status === 'confirmed') {
                    $this->log($member, 'contribution_approved', 'Contribution '.$contribution->payment_reference.' approved.', ['contribution_id' => $contribution->id]);
                    $this->notify($member, 'Contribution approved', 'Your cooperative contribution has been confirmed.', route('member.contributions.show', $contribution));
                }

                if ($contribution->status === 'rejected') {
                    $this->log($member, 'contribution_rejected', 'Contribution '.$contribution->payment_reference.' rejected.', ['contribution_id' => $contribution->id]);
                    $this->notify($member, 'Contribution rejected', 'A contribution requires updated payment evidence.', route('member.contributions.show', $contribution));
                }
            }

            Settlement::where('user_id', $member->id)->where('status', 'completed')->get()->each(function (Settlement $settlement) use ($member): void {
                $this->log($member, 'settlement_updated', 'Settlement '.$settlement->reference_number.' processed.', ['settlement_id' => $settlement->id]);
                $this->notify($member, 'Settlement processed', 'Your cooperative settlement has been processed.', route('member.dashboard'));
            });
        }
    }

    private function log(User $user, string $action, string $description, array $metadata): void
    {
        ActivityLog::updateOrCreate(
            [
                'user_id' => $user->id,
                'action' => $action,
                'description' => $description,
            ],
            [
                'metadata' => $metadata + ['source' => 'development_seed'],
                'ip_address' => '127.0.0.1',
            ]
        );
    }

    private function notify(User $user, string $title, string $body, string $url): void
    {
        $hash = md5($user->id.'|'.$title.'|'.$url);
        $id = substr($hash, 0, 8).'-'.substr($hash, 8, 4).'-'.substr($hash, 12, 4).'-'.substr($hash, 16, 4).'-'.substr($hash, 20, 12);

        DatabaseNotification::query()->updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'type' => 'database.demo',
                'data' => [
                    'title' => $title,
                    'body' => $body,
                    'url' => $url,
                    'source' => 'development_seed',
                ],
            ]
        );
    }
}
