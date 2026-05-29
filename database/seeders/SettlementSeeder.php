<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SettlementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@secureportal.test')->firstOrFail();
        $members = User::where('role', 'member')->orderBy('id')->take(8)->get();
        $batches = Batch::orderBy('id')->get();

        foreach ($members as $index => $member) {
            $status = ['completed', 'pending', 'processing', 'completed', 'failed', 'pending', 'completed', 'cancelled'][$index];

            Settlement::updateOrCreate(
                ['reference_number' => 'CCA-SETTLEMENT-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'user_id' => $member->id,
                    'batch_id' => $batches[$index % max(1, $batches->count())]->id,
                    'amount' => [4500, 2200, 1750, 3900, 1200, 950, 2600, 800][$index],
                    'status' => $status,
                    'processed_by_admin_id' => in_array($status, ['completed', 'failed', 'cancelled'], true) ? $admin->id : null,
                    'processed_at' => $status === 'completed' ? now()->subDays(18 - $index) : null,
                    'notes' => Str::of($status)->title().' demo settlement for analytics testing.',
                ]
            );
        }
    }
}
