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
            ['name' => 'Adebayo Okafor', 'email' => 'member1@secureportal.test', 'phone' => '08011111111', 'status' => 'active'],
            ['name' => 'Chioma Nwosu', 'email' => 'member2@secureportal.test', 'phone' => '08022222222', 'status' => 'active'],
            ['name' => 'Tunde Balogun', 'email' => 'member3@secureportal.test', 'phone' => '08033333333', 'status' => 'active'],
            ['name' => 'Amina Bello', 'email' => 'amina.bello@secureportal.test', 'phone' => '08044444444', 'status' => 'active'],
            ['name' => 'Kelechi Eze', 'email' => 'kelechi.eze@secureportal.test', 'phone' => '08055555555', 'status' => 'active'],
            ['name' => 'Musa Ibrahim', 'email' => 'musa.ibrahim@secureportal.test', 'phone' => '08066666666', 'status' => 'inactive'],
            ['name' => 'Yetunde Adeyemi', 'email' => 'yetunde.adeyemi@secureportal.test', 'phone' => '08077777777', 'status' => 'active'],
            ['name' => 'Ifeanyi Obi', 'email' => 'ifeanyi.obi@secureportal.test', 'phone' => '08088888888', 'status' => 'inactive'],
            ['name' => 'Zainab Sani', 'email' => 'zainab.sani@secureportal.test', 'phone' => '08099999999', 'status' => 'active'],
            ['name' => 'Folake Williams', 'email' => 'folake.williams@secureportal.test', 'phone' => '08100000000', 'status' => 'active'],
        ];

        foreach ($members as $index => $memberData) {
            $member = User::updateOrCreate(
                ['email' => $memberData['email']],
                [
                    'name' => $memberData['name'],
                    'phone' => $memberData['phone'],
                    'password' => Hash::make('Member@123'),
                    'role' => 'member',
                    'reference_token' => 'CCA-MEMBER-DEMO-'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'status' => $memberData['status'],
                    'email_verified_at' => now(),
                ]
            );

            $this->seedMemberProfile($member, $index);
            $this->seedSettlementProfile($member, $index);

            if ($index !== 7) {
                $batch = $batches[$index % $batches->count()];
                $token = AccessToken::updateOrCreate(
                    ['token' => 'CCA-DEMO-ACCESS-'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)],
                    [
                        'batch_id' => $batch->id,
                        'ownership_tier' => ['standard', 'growth', 'premium', 'founder'][$index % 4],
                        'assigned_to_user_id' => $member->id,
                        'status' => 'used',
                        'expires_at' => now()->addMonths(2),
                        'used_at' => now()->subDays(25 - $index),
                        'revoked_at' => null,
                        'created_by_admin_id' => $admin->id,
                    ]
                );

                BatchMember::updateOrCreate(
                    ['batch_id' => $batch->id, 'user_id' => $member->id],
                    [
                        'access_token_id' => $token->id,
                        'participation_status' => $memberData['status'] === 'active' ? 'active' : 'suspended',
                        'joined_at' => now()->subDays(25 - $index),
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
            ['title' => 'Lagos Greenhouse Harvest Cycle', 'level' => 'premium', 'fee' => 250000],
            ['title' => 'Ogun Cassava Cooperative Yield', 'level' => 'standard', 'fee' => 150000],
            ['title' => 'Kaduna Irrigation Ownership Acre', 'level' => 'growth', 'fee' => 200000],
        ])->map(fn (array $batch) => Batch::updateOrCreate(
            ['slug' => Str::slug($batch['title'])],
            [
                'title' => $batch['title'],
                'description' => 'Demo Harvest Cycle for ownership intelligence, contribution analytics, and settlement testing.',
                'batch_code' => 'CCA-BATCH-'.Str::upper(Str::substr(md5($batch['title']), 0, 6)),
                'start_date' => now()->subMonth()->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
                'status' => 'active',
                'max_members' => 50,
                'ownership_level' => $batch['level'],
                'participation_fee' => $batch['fee'],
                'is_active' => true,
            ]
        ));
    }

    private function seedMemberProfile(User $member, int $index): void
    {
        $states = ['Lagos', 'Ogun', 'Abuja', 'Rivers', 'Kaduna'];
        $cities = ['Lekki', 'Abeokuta', 'Garki', 'Port Harcourt', 'Kaduna'];

        MemberProfile::updateOrCreate(
            ['user_id' => $member->id],
            [
                'full_legal_name' => $member->name,
                'phone' => $member->phone,
                'date_of_birth' => now()->subYears(28 + $index)->toDateString(),
                'gender' => ['male', 'female', 'prefer_not_to_say'][$index % 3],
                'country' => 'Nigeria',
                'state' => $states[$index % count($states)],
                'city' => $cities[$index % count($cities)],
                'residential_address' => ($index + 12).' Cooperative Registry Avenue',
                'postal_code' => '10000'.$index,
                'occupation' => ['Product Manager', 'Agro Investor', 'Operations Lead', 'Civil Servant', 'Founder'][$index % 5],
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
        $banks = ['Access Bank', 'GTBank', 'Zenith Bank', 'First Bank', 'UBA', 'Sterling Bank', 'Providus Bank'];

        SettlementProfile::updateOrCreate(
            ['user_id' => $member->id],
            [
                'bank_name' => $banks[$index % count($banks)],
                'account_name' => $member->name,
                'account_number' => '10'.str_pad((string) ($index + 10000000), 8, '0', STR_PAD_LEFT),
                'routing_number' => '0'.($index + 10),
                'country' => 'NG',
                'currency' => 'NGN',
                'verification_status' => $index === 5 ? 'pending' : ($index === 7 ? 'rejected' : 'verified'),
                'rejection_reason' => $index === 7 ? 'Demo incomplete banking verification.' : null,
                'verified_at' => in_array($index, [5, 7], true) ? null : now()->subDays(30 - $index),
            ]
        );
    }
}
