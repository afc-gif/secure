<?php

namespace Database\Seeders;

use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\BatchMember;
use App\Models\MemberProfile;
use App\Models\SettlementProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@secureportal.test')->firstOrFail();
        $batches = $this->seedBatches();

        $members = [
            ['name' => 'Michael Anderson', 'email' => 'michael.anderson@secureportal.test', 'phone' => '2125550101', 'status' => 'active'],
            ['name' => 'Emily Carter', 'email' => 'emily.carter@secureportal.test', 'phone' => '3125550102', 'status' => 'active'],
            ['name' => 'James Wilson', 'email' => 'james.wilson@secureportal.test', 'phone' => '4155550103', 'status' => 'active'],
            ['name' => 'Sarah Mitchell', 'email' => 'sarah.mitchell@secureportal.test', 'phone' => '6175550104', 'status' => 'active'],
            ['name' => 'David Thompson', 'email' => 'david.thompson@secureportal.test', 'phone' => '2065550105', 'status' => 'active'],
            ['name' => 'Jessica Brown', 'email' => 'jessica.brown@secureportal.test', 'phone' => '3055550106', 'status' => 'inactive'],
            ['name' => 'Robert Davis', 'email' => 'robert.davis@secureportal.test', 'phone' => '7205550107', 'status' => 'active'],
            ['name' => 'Ashley Miller', 'email' => 'ashley.miller@secureportal.test', 'phone' => '4045550108', 'status' => 'inactive'],
            ['name' => 'Christopher Moore', 'email' => 'christopher.moore@secureportal.test', 'phone' => '6025550109', 'status' => 'active'],
            ['name' => 'Amanda Taylor', 'email' => 'amanda.taylor@secureportal.test', 'phone' => '5035550110', 'status' => 'active'],
        ];

        foreach ($members as $index => $memberData) {
            $referenceToken = 'CCA-MEMBER-DEMO-'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);

            $member = User::updateOrCreate(
                ['reference_token' => $referenceToken],
                [
                    'name' => $memberData['name'],
                    'email' => $memberData['email'],
                    'phone' => $memberData['phone'],
                    'password' => Hash::make('Member@123'),
                    'role' => 'member',
                    'status' => $memberData['status'],
                    'email_verified_at' => now(),
                ]
            );

            $this->seedMemberProfile($member, $index);
            $this->seedSettlementProfile($member, $index);

            if ($index !== 7) {
                $batch = $batches[$index % $batches->count()];
                $token = AccessToken::updateOrCreate(
                    ['token' => 'VIP'.str_pad((string) ($index + 1), 10, '0', STR_PAD_LEFT)],
                    [
                        'batch_id' => $batch->id,
                        'ownership_tier' => 'Batch 3 Synchronized Class',
                        'assigned_to_user_id' => $member->id,
                        'status' => 'used',
                        'expires_at' => now()->addMonths(2),
                        'used_at' => '2026-06-07 00:00:00',
                        'revoked_at' => null,
                        'created_by_admin_id' => $admin->id,
                    ]
                );

                BatchMember::updateOrCreate(
                    ['batch_id' => $batch->id, 'user_id' => $member->id],
                    [
                        'access_token_id' => $token->id,
                        'participation_status' => $memberData['status'] === 'active' ? 'active' : 'suspended',
                        'joined_at' => '2026-06-07 00:00:00',
                    ]
                );
            }
        }

        foreach ($batches as $batch) {
            $batch->update(['current_members' => $batch->batchMembers()->count()]);
        }
    }

    private function seedBatches()
    {
        return collect([
            ['title' => 'Batch 3 Entertainment Cycle', 'level' => 'Batch 3 Synchronized Class', 'fee' => 2500, 'legacy_slugs' => ['lagos-greenhouse-harvest-cycle', 'california-greenhouse-harvest-cycle']],
            ['title' => 'Iowa Corn Cooperative Yield', 'level' => 'standard', 'fee' => 1500, 'legacy_slugs' => ['ogun-cassava-cooperative-yield']],
            ['title' => 'Nebraska Irrigation Ownership Acre', 'level' => 'growth', 'fee' => 2000, 'legacy_slugs' => ['kaduna-irrigation-ownership-acre']],
        ])->map(function (array $batch) {
            $slug = Str::slug($batch['title']);
            $model = Batch::firstOrNew([
                'slug' => $slug,
            ]);

            if (! $model->exists) {
                $model = Batch::whereIn('slug', $batch['legacy_slugs'])->first() ?? $model;
            }

            $model->fill([
                'title' => $batch['title'],
                'slug' => $slug,
                'description' => 'Demo Harvest Cycle for ownership intelligence, contribution analytics, and settlement testing.',
                'batch_code' => 'CCA-BATCH-'.Str::upper(Str::substr(md5($batch['title']), 0, 6)),
                'start_date' => now()->subMonth()->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
                'status' => 'active',
                'max_members' => 50,
                'ownership_level' => $batch['level'],
                'participation_fee' => $batch['fee'],
                'is_active' => true,
            ])->save();

            return $model;
        });
    }

    private function seedMemberProfile(User $member, int $index): void
    {
        $states = ['NY', 'IL', 'CA', 'MA', 'WA', 'FL', 'CO', 'GA', 'AZ', 'OR'];
        $cities = ['New York', 'Chicago', 'San Francisco', 'Boston', 'Seattle', 'Miami', 'Denver', 'Atlanta', 'Phoenix', 'Portland'];
        $postalCodes = ['10001', '60601', '94105', '02110', '98101', '33101', '80202', '30303', '85004', '97205'];

        MemberProfile::updateOrCreate(
            ['user_id' => $member->id],
            [
                'full_legal_name' => $member->name,
                'phone' => $member->phone,
                'date_of_birth' => now()->subYears(28 + $index)->toDateString(),
                'gender' => ['male', 'female', 'prefer_not_to_say'][$index % 3],
                'country' => 'United States',
                'state' => $states[$index % count($states)],
                'city' => $cities[$index % count($cities)],
                'residential_address' => ($index + 120).' Maple Ridge Avenue',
                'postal_code' => $postalCodes[$index % count($postalCodes)],
                'occupation' => ['Product Manager', 'Agricultural Investor', 'Operations Lead', 'Consultant', 'Founder'][$index % 5],
                'ownership_interest_reason' => 'Demo member interested in structured agricultural ownership and cooperative yield.',
                'agricultural_interest_type' => ['crop_cycles', 'livestock', 'greenhouse', 'irrigation'][$index % 4],
                'bio' => 'Development demo profile for Secure Portal ownership analytics.',
                'onboarding_completed' => $index !== 7,
                'onboarding_completed_at' => $index !== 7 ? now()->subDays(40 - $index) : null,
            ]
        );
    }

    private function seedSettlementProfile(User $member, int $index): void
    {
        $banks = ['Chase Bank', 'Bank of America', 'Wells Fargo', 'Citibank', 'Capital One', 'U.S. Bank', 'PNC Bank'];

        SettlementProfile::updateOrCreate(
            ['user_id' => $member->id],
            [
                'bank_name' => $banks[$index % count($banks)],
                'account_name' => $member->name,
                'account_number' => str_pad((string) ($index + 1000000000), 10, '0', STR_PAD_LEFT),
                'routing_number' => str_pad((string) ($index + 110000001), 9, '0', STR_PAD_LEFT),
                'country' => 'US',
                'currency' => 'USD',
                'verification_status' => $index === 5 ? 'pending' : ($index === 7 ? 'rejected' : 'verified'),
                'rejection_reason' => $index === 7 ? 'Demo incomplete banking verification.' : null,
                'verified_at' => in_array($index, [5, 7], true) ? null : now()->subDays(30 - $index),
            ]
        );
    }
}
