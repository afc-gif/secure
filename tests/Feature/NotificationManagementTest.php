<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_and_mark_notifications_read(): void
    {
        $user = User::factory()->create();
        $notification = $this->notificationFor($user, [
            'title' => 'Payment approved',
            'body' => 'Your dashboard access is active.',
            'url' => route('dashboard'),
        ]);

        $this->actingAs($user)
            ->get(route('notifications.index'))
            ->assertOk()
            ->assertSee('Notification Center')
            ->assertSee('Payment approved');

        $this->actingAs($user)
            ->post(route('notifications.read', $notification))
            ->assertRedirect();

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_cannot_manage_another_users_notification(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $notification = $this->notificationFor($owner);

        $this->actingAs($other)
            ->post(route('notifications.read', $notification))
            ->assertNotFound();
    }

    public function test_open_notification_marks_it_read_and_redirects_to_payload_url(): void
    {
        $user = User::factory()->create();
        $notification = $this->notificationFor($user, [
            'title' => 'Open dashboard',
            'body' => 'Go to dashboard.',
            'url' => route('dashboard'),
        ]);

        $this->actingAs($user)
            ->get(route('notifications.open', $notification))
            ->assertRedirect(route('dashboard'));

        $this->assertNotNull($notification->fresh()->read_at);
    }

    private function notificationFor(User $user, array $data = []): DatabaseNotification
    {
        return DatabaseNotification::query()->create([
            'id' => fake()->uuid(),
            'type' => 'database.test',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => array_merge([
                'title' => 'Portal update',
                'body' => 'New account activity.',
                'url' => route('dashboard'),
            ], $data),
        ]);
    }
}
