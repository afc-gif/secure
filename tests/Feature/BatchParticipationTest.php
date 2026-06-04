<?php

namespace Tests\Feature;

use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\BatchMember;
use App\Models\MemberProfile;
use App\Models\User;
use App\Notifications\VipPaymentSubmittedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class BatchParticipationTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_activate_valid_access_token(): void
    {
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();
        $batch = Batch::factory()->create();
        $token = AccessToken::factory()->for($batch)->create();

        $this->actingAs($member)
            ->post(route('member.access-token.store'), ['token' => $token->token])
            ->assertRedirect(route('member.participation.index'));

        $this->assertDatabaseHas('batch_members', [
            'batch_id' => $batch->id,
            'user_id' => $member->id,
            'access_token_id' => $token->id,
            'participation_status' => 'active',
        ]);

        $this->assertDatabaseHas('access_tokens', [
            'id' => $token->id,
            'status' => 'used',
            'assigned_to_user_id' => $member->id,
        ]);

        $this->assertSame(1, $batch->refresh()->current_members);
    }

    public function test_revoked_token_cannot_be_activated(): void
    {
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();
        $token = AccessToken::factory()->revoked()->create();

        $this->actingAs($member)
            ->from(route('member.access-token.create'))
            ->post(route('member.access-token.store'), ['token' => $token->token])
            ->assertRedirect(route('member.access-token.create'))
            ->assertSessionHasErrors('token');

        $this->assertDatabaseCount('batch_members', 0);
    }

    public function test_unlocked_member_is_redirected_away_from_token_activation(): void
    {
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();
        $batch = Batch::factory()->create();
        $usedToken = AccessToken::factory()->for($batch)->used()->create(['assigned_to_user_id' => $member->id]);
        BatchMember::create([
            'batch_id' => $batch->id,
            'user_id' => $member->id,
            'access_token_id' => $usedToken->id,
            'participation_status' => 'active',
            'joined_at' => now(),
        ]);
        $freshToken = AccessToken::factory()->for($batch)->create();

        $this->actingAs($member)
            ->from(route('member.access-token.create'))
            ->post(route('member.access-token.store'), ['token' => $freshToken->token])
            ->assertRedirect(route('member.dashboard'));

        $this->assertDatabaseCount('batch_members', 1);
        $this->assertDatabaseHas('access_tokens', [
            'id' => $freshToken->id,
            'status' => 'active',
            'assigned_to_user_id' => null,
        ]);
    }

    public function test_unlocked_member_keeps_vip_token_tab_without_payment_controls(): void
    {
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();
        $batch = Batch::factory()->create();
        $usedToken = AccessToken::factory()->for($batch)->used()->create(['assigned_to_user_id' => $member->id]);
        BatchMember::create([
            'batch_id' => $batch->id,
            'user_id' => $member->id,
            'access_token_id' => $usedToken->id,
            'participation_status' => 'active',
            'joined_at' => now(),
        ]);

        $this->actingAs($member)
            ->get(route('member.dashboard'))
            ->assertOk()
            ->assertSee('VIP Token');

        $this->actingAs($member)
            ->get(route('member.access-token.create'))
            ->assertOk()
            ->assertSee('Dashboard access active')
            ->assertDontSee('VIP Bitcoin Payment')
            ->assertDontSee('Confirm Payment')
            ->assertDontSee('BTC Wallet');
    }

    public function test_member_can_confirm_vip_btc_payment_for_admin_review(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();
        $batch = Batch::factory()->create();
        $paymentToken = AccessToken::factory()->for($batch)->create([
            'price' => 250.00,
            'price_currency' => 'USD',
            'btc_wallet_address' => 'bc1qsecureportalvipwallet000000000000000',
        ]);

        $this->actingAs($member)
            ->post(route('member.access-token.payment.confirm'), [
                'payment_token_id' => $paymentToken->id,
                'btc_transaction_reference' => 'btc-tx-123',
                'payment_notes' => 'Paid from Cash App BTC wallet.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('contributions', [
            'user_id' => $member->id,
            'batch_id' => $batch->id,
            'amount' => 250.00,
            'currency' => 'USD',
            'contribution_type' => 'batch_participation',
            'status' => 'pending',
        ]);

        Notification::assertSentTo($admin, VipPaymentSubmittedNotification::class);
    }
}
