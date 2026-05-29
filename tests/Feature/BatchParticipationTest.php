<?php

namespace Tests\Feature;

use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\BatchMember;
use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_member_cannot_join_same_batch_twice(): void
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
            ->assertRedirect(route('member.access-token.create'))
            ->assertSessionHasErrors('token');
    }
}
