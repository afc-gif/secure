<?php

namespace Tests\Feature;

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
        $this->actingAs($admin)->get(route('admin.tokens.index'))->assertOk()->assertSee('Access token management');
    }

    public function test_onboarded_member_can_render_phase_three_pages(): void
    {
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();

        $this->actingAs($member)->get(route('member.batches.index'))->assertOk()->assertSee('Active ownership cycles');
        $this->actingAs($member)->get(route('member.access-token.create'))->assertOk()->assertSee('Cooperative access token');
        $this->actingAs($member)->get(route('member.participation.index'))->assertOk()->assertSee('Participation status');
    }
}
