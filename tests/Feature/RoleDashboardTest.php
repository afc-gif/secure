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
            ->assertSee('Administrative command center');
    }

    public function test_member_can_access_member_dashboard(): void
    {
        $member = User::factory()->create();
        MemberProfile::factory()->for($member)->completed()->create();

        $this->actingAs($member)
            ->get('/member/dashboard')
            ->assertOk()
            ->assertSee('Member Benefit Vault')
            ->assertSee('Total secured benefit balance')
            ->assertSee('USD 33,000.00');
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
