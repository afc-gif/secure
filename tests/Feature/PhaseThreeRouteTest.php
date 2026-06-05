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
        $member = User::factory()->create(['name' => 'Signed Up Member']);

        $this->actingAs($admin)->get(route('admin.batches.index'))->assertOk()->assertSee('Active cycle management');
        $this->actingAs($admin)->get(route('admin.tokens.index'))->assertOk()->assertSee('Secure Access Token Management');
        $this->actingAs($admin)
            ->get(route('admin.partners.index'))
            ->assertOk()
            ->assertSee('Signed up members and participants')
            ->assertSee('Signed Up Member')
            ->assertSee($member->reference_token);
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

    public function test_admin_can_manage_active_cycles_that_sync_to_member_cycle_tab(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();
        $unlockBatch = Batch::factory()->create();
        $token = AccessToken::factory()->used()->for($unlockBatch)->create([
            'assigned_to_user_id' => $member->id,
        ]);

        BatchMember::create([
            'batch_id' => $unlockBatch->id,
            'user_id' => $member->id,
            'access_token_id' => $token->id,
            'participation_status' => 'active',
            'joined_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.batches.store'), [
                'title' => 'Studio Revenue Stream',
                'description' => 'Admin-created secured revenue stream.',
                'batch_code' => 'SECURE-STUDIO-99',
                'start_date' => '2026-06-01',
                'end_date' => '2026-11-01',
                'status' => 'active',
                'max_members' => 100,
                'ownership_level' => 'Catalog Equity Class',
                'participation_fee' => 390000,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.batches.index'));

        $batch = Batch::where('batch_code', 'SECURE-STUDIO-99')->firstOrFail();

        $this->assertDatabaseHas('batches', [
            'id' => $batch->id,
            'title' => 'Studio Revenue Stream',
            'status' => 'active',
            'is_active' => true,
        ]);

        $this->actingAs($member)
            ->get(route('member.batches.index'))
            ->assertOk()
            ->assertSee('Studio Revenue Stream')
            ->assertSee('SECURE-STUDIO-99');

        $this->actingAs($admin)
            ->put(route('admin.batches.update', $batch), [
                'title' => 'Edited Studio Revenue Stream',
                'description' => 'Edited admin-created secured revenue stream.',
                'batch_code' => 'SECURE-STUDIO-EDIT',
                'start_date' => '2026-07-01',
                'end_date' => '2026-12-01',
                'status' => 'active',
                'max_members' => 125,
                'ownership_level' => 'Catalog Equity Class',
                'participation_fee' => 410000,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.batches.index'));

        $this->assertDatabaseHas('batches', [
            'id' => $batch->id,
            'title' => 'Edited Studio Revenue Stream',
            'batch_code' => 'SECURE-STUDIO-EDIT',
            'participation_fee' => 410000,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.batches.destroy', $batch))
            ->assertRedirect(route('admin.batches.index'));

        $this->assertDatabaseMissing('batches', ['id' => $batch->id]);
    }

    public function test_pending_admin_cycles_are_visible_on_member_cycle_tab(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();
        $unlockBatch = Batch::factory()->create();
        $token = AccessToken::factory()->used()->for($unlockBatch)->create([
            'assigned_to_user_id' => $member->id,
        ]);

        BatchMember::create([
            'batch_id' => $unlockBatch->id,
            'user_id' => $member->id,
            'access_token_id' => $token->id,
            'participation_status' => 'active',
            'joined_at' => now(),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.batches.store'), [
                'title' => 'Pending Studio Revenue Stream',
                'description' => 'Pending secured revenue stream.',
                'batch_code' => 'SECURE-PENDING-01',
                'start_date' => '2026-06-01',
                'end_date' => '2026-11-01',
                'status' => 'pending',
                'max_members' => 100,
                'ownership_level' => 'Catalog Equity Class',
                'participation_fee' => 390000,
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.batches.index'));

        $this->assertDatabaseHas('batches', [
            'batch_code' => 'SECURE-PENDING-01',
            'status' => 'pending',
            'is_active' => true,
        ]);

        $this->actingAs($member)
            ->get(route('member.batches.index'))
            ->assertOk()
            ->assertSee('Pending Studio Revenue Stream')
            ->assertSee('pending')
            ->assertSee('SECURED POSITION PENDING');
    }

    public function test_admin_can_delete_member_from_partner_registry(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();

        $this->actingAs($admin)
            ->delete(route('admin.partners.destroy', $member))
            ->assertRedirect(route('admin.partners.index'));

        $this->assertDatabaseMissing('users', ['id' => $member->id]);
        $this->assertDatabaseMissing('member_profiles', ['user_id' => $member->id]);
    }

    public function test_admin_cannot_delete_admin_from_partner_registry(): void
    {
        $admin = User::factory()->admin()->create();
        $otherAdmin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete(route('admin.partners.destroy', $otherAdmin))
            ->assertNotFound();

        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id]);
    }
}
