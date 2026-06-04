<?php

namespace Tests\Feature;

use App\Models\MemberProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Entertainment Asset Console')
            ->assertSee('USD 780,000.00');
    }

    public function test_member_can_access_member_dashboard(): void
    {
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();

        $this->actingAs($member)
            ->get('/member/dashboard')
            ->assertOk()
            ->assertSee('Member Portfolio')
            ->assertSee('Secured Benefit Balance')
            ->assertSee('USD 34,000.00')
            ->assertSee('Sovereign Equity')
            ->assertSee('USD 24,911.34')
            ->assertSee('Cleared')
            ->assertSee('Legacy Grounds Dividend Track')
            ->assertSee('USD 9,088.66')
            ->assertSee('Pending');
    }

    public function test_onboarded_member_without_vip_token_sees_locked_dashboard(): void
    {
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();

        $this->actingAs($member)
            ->get('/member/dashboard')
            ->assertOk()
            ->assertSee('VIP Locked')
            ->assertSee('VIP token required');
    }

    public function test_incomplete_member_is_redirected_to_onboarding(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)
            ->get('/member/dashboard')
            ->assertRedirect(route('onboarding.index', absolute: false));
    }

    public function test_member_cannot_access_admin_dashboard(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }
}
