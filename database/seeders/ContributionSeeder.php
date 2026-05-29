<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Contribution;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContributionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@secureportal.test')->firstOrFail();
        $members = User::where('role', 'member')->orderBy('id')->get();
        $batches = Batch::orderBy('id')->get();

        $levels = [35000, 27000, 18000, 12500, 9500, 7000, 5200, 3000, 2100, 1500];

        foreach ($members as $index => $member) {
            $batch = $batches[$index % max(1, $batches->count())] ?? null;
            $baseAmount = $levels[$index] ?? 150000;

            $this->seedContribution($member, $batch, $admin, [
                'payment_reference' => 'CCA-CONFIRMED-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                'amount' => $baseAmount,
                'status' => 'confirmed',
                'contribution_type' => ['cooperative_buy_in', 'ownership_expansion', 'batch_participation'][$index % 3],
                'approved_at' => now()->subDays(70 - ($index * 4)),
                'notes' => 'Confirmed development contribution for ownership percentage testing.',
                'admin_notes' => 'Approved after demo payment verification.',
            ]);

            if ($index % 2 === 0) {
                $this->seedContribution($member, $batch, $admin, [
                    'payment_reference' => 'CCA-PENDING-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'amount' => round($baseAmount * 0.25, 2),
                    'status' => 'pending',
                    'contribution_type' => 'settlement_reserve',
                    'approved_at' => null,
                    'approved_by_admin_id' => null,
                    'notes' => 'Pending top-up for next Harvest Cycle.',
                    'admin_notes' => null,
                ]);
            }

            if (in_array($index, [2, 5, 8], true)) {
                $this->seedContribution($member, $batch, $admin, [
                    'payment_reference' => 'CCA-REJECTED-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'amount' => round($baseAmount * 0.12, 2),
                    'status' => 'rejected',
                    'contribution_type' => 'ownership_expansion',
                    'approved_at' => now()->subDays(12 - ($index % 3)),
                    'notes' => 'Demo rejected contribution request.',
                    'admin_notes' => 'Rejected pending clearer payment evidence.',
                ]);
            }
        }
    }

    private function seedContribution(User $member, ?Batch $batch, User $admin, array $data): void
    {
        Contribution::updateOrCreate(
            ['payment_reference' => $data['payment_reference']],
            [
                'user_id' => $member->id,
                'batch_id' => $batch?->id,
                'amount' => $data['amount'],
                'currency' => 'USD',
                'status' => $data['status'],
                'contribution_type' => $data['contribution_type'],
                'notes' => $data['notes'],
                'admin_notes' => $data['admin_notes'],
                'approved_by_admin_id' => $data['approved_by_admin_id'] ?? ($data['status'] === 'pending' ? null : $admin->id),
                'approved_at' => $data['approved_at'],
                'created_at' => $data['approved_at']?->copy()->subDays(2) ?? now()->subDays(3),
                'updated_at' => now(),
            ]
        );
    }
}
