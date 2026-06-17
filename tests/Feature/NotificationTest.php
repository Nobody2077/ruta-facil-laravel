<?php

namespace Tests\Feature;

use App\Models\Opinion;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NuevaOpinionNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug): User
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => $slug]);
        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        return $user;
    }

    private function opinionPayload(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Vecino Anónimo',
            'route'   => 'Ceja - Rio Seco',
            'message' => 'El minibus tarda demasiado en las mañanas.',
        ], $overrides);
    }

    // --- disparo de la alarma ---

    public function test_new_opinion_notifies_admin_and_moderador(): void
    {
        Notification::fake();

        $admin   = $this->userWithRole('admin');
        $mod     = $this->userWithRole('moderador');
        $usuario = $this->userWithRole('usuario');

        $this->post(route('opinions.store'), $this->opinionPayload());

        Notification::assertSentTo([$admin, $mod], NuevaOpinionNotification::class);
        Notification::assertNotSentTo([$usuario], NuevaOpinionNotification::class);
    }

    public function test_author_is_not_notified_of_own_opinion(): void
    {
        Notification::fake();

        $admin = $this->userWithRole('admin');

        $this->actingAs($admin)->post(route('opinions.store'), $this->opinionPayload());

        Notification::assertNotSentTo([$admin], NuevaOpinionNotification::class);
    }

    // --- centro de notificaciones web ---

    public function test_notifications_index_requires_auth(): void
    {
        $this->get(route('notificaciones.index'))->assertRedirect(route('login'));
    }

    public function test_notifications_index_ok_for_authenticated_user(): void
    {
        $admin = $this->userWithRole('admin');
        $admin->notify(new NuevaOpinionNotification(Opinion::factory()->create()));

        $this->actingAs($admin)
            ->get(route('notificaciones.index'))
            ->assertStatus(200)
            ->assertSee('Centro de notificaciones');
    }

    public function test_mark_single_notification_as_read(): void
    {
        $admin = $this->userWithRole('admin');
        $admin->notify(new NuevaOpinionNotification(Opinion::factory()->create()));

        $this->assertEquals(1, $admin->unreadNotifications()->count());
        $id = $admin->notifications()->first()->id;

        $this->actingAs($admin)
            ->post(route('notificaciones.read', $id))
            ->assertRedirect();

        $this->assertEquals(0, $admin->fresh()->unreadNotifications()->count());
    }

    public function test_mark_all_notifications_as_read(): void
    {
        $admin = $this->userWithRole('admin');
        $admin->notify(new NuevaOpinionNotification(Opinion::factory()->create()));
        $admin->notify(new NuevaOpinionNotification(Opinion::factory()->create()));

        $this->assertEquals(2, $admin->unreadNotifications()->count());

        $this->actingAs($admin)
            ->post(route('notificaciones.read-all'))
            ->assertRedirect();

        $this->assertEquals(0, $admin->fresh()->unreadNotifications()->count());
    }

    // --- API ---

    public function test_api_notifications_requires_auth(): void
    {
        $this->getJson('/api/notifications')->assertStatus(401);
    }

    public function test_api_notifications_index_returns_unread_count(): void
    {
        $admin = $this->userWithRole('admin');
        $admin->notify(new NuevaOpinionNotification(Opinion::factory()->create()));

        $this->actingAs($admin, 'sanctum')
            ->getJson('/api/notifications')
            ->assertStatus(200)
            ->assertJsonPath('unread_count', 1)
            ->assertJsonCount(1, 'data');
    }

    public function test_api_mark_as_read(): void
    {
        $admin = $this->userWithRole('admin');
        $admin->notify(new NuevaOpinionNotification(Opinion::factory()->create()));
        $id = $admin->notifications()->first()->id;

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/notifications/{$id}/read")
            ->assertStatus(200);

        $this->assertEquals(0, $admin->fresh()->unreadNotifications()->count());
    }
}
