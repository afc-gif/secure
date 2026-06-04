<?php

namespace Tests\Feature;

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
}
