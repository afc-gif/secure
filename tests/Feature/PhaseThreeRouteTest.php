<?php

namespace Tests\Feature;

use App\Models\AccessToken;
use App\Models\Batch;
use App\Models\BatchMember;
use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseThreeRouteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_render_phase_three_management_pages(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.batches.index'))->assertOk()->assertSee('Batch cycle management');
        $this->actingAs($admin)->get(route('admin.tokens.index'))->assertOk()->assertSee('Secure Access Token Management');
    }

    public function test_onboarded_member_needs_vip_token_to_render_locked_phase_three_pages(): void
    {
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();

        $this->actingAs($member)->get(route('member.batches.index'))->assertRedirect(route('member.dashboard'));
        $this->actingAs($member)->get(route('member.access-token.create'))->assertOk()->assertSee('Secure Access Token');
        $this->actingAs($member)->get(route('member.participation.index'))->assertRedirect(route('member.dashboard'));

        $batch = Batch::factory()->create();
        $token = AccessToken::factory()->used()->for($batch)->create([
            'assigned_to_user_id' => $member->id,
        ]);

        BatchMember::create([
            'batch_id' => $batch->id,
            'user_id' => $member->id,
            'access_token_id' => $token->id,
            'participation_status' => 'active',
            'joined_at' => now(),
        ]);

        $this->actingAs($member)->get(route('member.batches.index'))->assertOk()->assertSee('Active ownership cycles');
        $this->actingAs($member)->get(route('member.participation.index'))->assertOk()->assertSee('Participation status');
    }
}
