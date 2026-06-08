<?php

namespace Tests\Feature;

use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\BatchMember;
use App\Models\MemberProfile;
use App\Models\SettlementProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_member_can_update_full_profile_and_cash_app_details(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
                'full_legal_name' => 'Test Legal User',
                'phone' => '+1 555 010 2222',
                'date_of_birth' => '1990-01-02',
                'gender' => 'prefer_not_to_say',
                'country' => 'United States',
                'state' => 'WY',
                'city' => 'Cheyenne',
                'residential_address' => '100 Main Street',
                'postal_code' => '82001',
                'cash_app_handle' => 'newcashapp',
                'occupation' => 'Operator',
                'agricultural_interest_type' => 'greenhouse',
                'ownership_interest_reason' => 'Updated ownership reason.',
                'bio' => 'Updated member bio.',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();
        $profile = $user->memberProfile;
        $settlement = $user->settlementProfile;

        $this->assertSame('+1 555 010 2222', $user->phone);
        $this->assertSame('Test Legal User', $profile->full_legal_name);
        $this->assertSame('100 Main Street', $profile->residential_address);
        $this->assertSame('$newcashapp', $profile->cash_app_handle);
        $this->assertSame('cash_app', $settlement->payout_platform);
        $this->assertSame('$newcashapp', $settlement->cash_app_handle);
        $this->assertSame('Cash App', $settlement->bank_name);
    }

    public function test_unlocked_member_can_add_bank_withdrawal_details(): void
    {
        $user = User::factory()->create();
        MemberProfile::factory()->for($user)->completed()->create(['cash_app_handle' => null]);
        $this->unlockMember($user);

        $this->actingAs($user)
            ->get(route('member.dashboard'))
            ->assertOk()
            ->assertSee('Add Bank Details');

        $this->actingAs($user)
            ->get(route('member.settlement-profile.show'))
            ->assertOk()
            ->assertSee('Bank Withdrawal')
            ->assertSee('Bank Name')
            ->assertSee('Account Holder Name')
            ->assertSee('Routing Number')
            ->assertSee('Account Number')
            ->assertSee('Account Type');

        $this->actingAs($user)
            ->post(route('member.settlement-profile.store'), [
                'bank_name' => 'Chase Bank',
                'account_name' => 'Test Legal User',
                'routing_number' => '123456789',
                'account_number' => '9876543210',
                'account_type' => 'checking',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('member.settlement-profile.show'));

        $settlement = $user->refresh()->settlementProfile;

        $this->assertSame('bank', $settlement->payout_platform);
        $this->assertNull($settlement->cash_app_handle);
        $this->assertSame('Chase Bank', $settlement->bank_name);
        $this->assertSame('Test Legal User', $settlement->account_name);
        $this->assertSame('123456789', $settlement->routing_number);
        $this->assertSame('9876543210', $settlement->account_number);
        $this->assertSame('checking', $settlement->account_type);

        $this->actingAs($user)
            ->get(route('member.settlement-profile.show'))
            ->assertOk()
            ->assertSee('Proceed to Withdraw');
    }

    public function test_member_can_proceed_to_withdrawal_and_it_completes_after_24_hours(): void
    {
        $user = User::factory()->create();
        MemberProfile::factory()->for($user)->completed()->create(['cash_app_handle' => null]);
        $profile = SettlementProfile::factory()->for($user)->create([
            'bank_name' => 'Chase Bank',
            'account_name' => 'Test Legal User',
            'routing_number' => '123456789',
            'account_number' => '9876543210',
            'account_type' => 'checking',
            'withdrawal_status' => null,
        ]);
        $this->unlockMember($user);

        $this->actingAs($user)
            ->post(route('member.settlement-profile.withdraw'), [
                'withdrawal_amount' => 5000,
            ])
            ->assertRedirect(route('member.settlement-profile.withdrawal-status'));

        $profile->refresh();
        $this->assertSame('processing', $profile->withdrawal_status);
        $this->assertSame('5000.00', $profile->withdrawal_amount);
        $this->assertNotNull($profile->withdrawal_requested_at);

        $this->actingAs($user)
            ->get(route('member.dashboard'))
            ->assertOk()
            ->assertSee('USD 29,000.00')
            ->assertSee('USD 5,000.00');

        $this->actingAs($user)
            ->get(route('member.settlement-profile.withdrawal-status'))
            ->assertOk()
            ->assertSee('Processing')
            ->assertSee('Your withdrawal will be complete within 24hrs')
            ->assertSee('Back to Dashboard');

        $profile->forceFill([
            'withdrawal_requested_at' => now()->subDay()->subMinute(),
        ])->save();

        $this->actingAs($user)
            ->get(route('member.settlement-profile.withdrawal-status'))
            ->assertOk()
            ->assertSee('Withdrawal Complete')
            ->assertSee('Your withdrawal has been completed')
            ->assertSee('Back to Dashboard');

        $this->assertSame('completed', $profile->refresh()->withdrawal_status);
        $this->assertSame('5000.00', $profile->total_withdrawn_amount);
        $this->assertNotNull($profile->withdrawal_completed_at);
    }

    public function test_dashboard_shows_withdraw_to_bank_when_bank_details_are_ready(): void
    {
        $user = User::factory()->create();
        MemberProfile::factory()->for($user)->completed()->create(['cash_app_handle' => null]);
        SettlementProfile::factory()->for($user)->create([
            'bank_name' => 'Chase Bank',
            'account_name' => 'Test Legal User',
            'verification_status' => 'verified',
        ]);
        $this->unlockMember($user);

        $this->actingAs($user)
            ->get(route('member.dashboard'))
            ->assertOk()
            ->assertSee('Withdraw to Bank')
            ->assertSee('Chase Bank');
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    private function unlockMember(User $user): void
    {
        $batch = Batch::factory()->create();
        $token = AccessToken::factory()->for($batch)->used()->create(['assigned_to_user_id' => $user->id]);

        BatchMember::create([
            'batch_id' => $batch->id,
            'user_id' => $user->id,
            'access_token_id' => $token->id,
            'participation_status' => 'active',
            'joined_at' => now(),
        ]);
    }
}
