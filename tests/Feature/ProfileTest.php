<?php

namespace Tests\Feature;

use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\BatchMember;
use App\Models\MemberProfile;
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

    public function test_unlocked_member_can_add_cash_app_withdrawal_details(): void
    {
        $user = User::factory()->create();
        MemberProfile::factory()->for($user)->completed()->create(['cash_app_handle' => null]);
        $this->unlockMember($user);

        $this->actingAs($user)
            ->get(route('member.dashboard'))
            ->assertOk()
            ->assertSee('Add Cash App Details');

        $this->actingAs($user)
            ->get(route('member.settlement-profile.show'))
            ->assertOk()
            ->assertSee('Cash App Withdrawal')
            ->assertSee('Cash App only')
            ->assertSee('No bank, card, crypto, or alternate payout method is accepted.');

        $this->actingAs($user)
            ->post(route('member.settlement-profile.store'), [
                'cash_app_handle' => 'cashready',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('member.settlement-profile.show'));

        $settlement = $user->refresh()->settlementProfile;

        $this->assertSame('cash_app', $settlement->payout_platform);
        $this->assertSame('$cashready', $settlement->cash_app_handle);
        $this->assertSame('Cash App', $settlement->bank_name);
        $this->assertSame('$cashready', $settlement->account_number);
    }

    public function test_dashboard_shows_withdraw_to_cash_app_when_cash_app_is_ready(): void
    {
        $user = User::factory()->create();
        MemberProfile::factory()->for($user)->completed()->create(['cash_app_handle' => '$cashready']);
        $this->unlockMember($user);

        $this->actingAs($user)
            ->get(route('member.dashboard'))
            ->assertOk()
            ->assertSee('Withdraw to Cash App')
            ->assertSee('Cash App ($cashready)');
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
