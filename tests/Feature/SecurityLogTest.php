<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityLogTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug): User
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => $slug]);
        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        return $user;
    }

    // --- registro automático de eventos ---

    public function test_successful_login_is_logged(): void
    {
        $user = User::factory()->create(['email' => 'tester@rf.test']);

        $this->post(route('login'), ['email' => 'tester@rf.test', 'password' => 'password']);

        $this->assertDatabaseHas('security_logs', [
            'event'   => 'login',
            'user_id' => $user->id,
        ]);
    }

    public function test_failed_login_is_logged(): void
    {
        User::factory()->create(['email' => 'tester@rf.test']);

        $this->post(route('login'), ['email' => 'tester@rf.test', 'password' => 'contrasena-incorrecta']);

        $this->assertDatabaseHas('security_logs', [
            'event' => 'login_failed',
            'email' => 'tester@rf.test',
        ]);
    }

    public function test_logout_is_logged(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user)->post(route('logout'));

        $this->assertDatabaseHas('security_logs', [
            'event'   => 'logout',
            'user_id' => $user->id,
        ]);
    }

    public function test_access_denied_is_logged(): void
    {
        $mod = $this->userWithRole('moderador');

        $this->actingAs($mod)->get(route('seguridad.logs'))->assertStatus(403);

        $this->assertDatabaseHas('security_logs', [
            'event'   => 'access_denied',
            'user_id' => $mod->id,
        ]);
    }

    // --- vista de auditoría (web) ---

    public function test_logs_page_redirects_guest(): void
    {
        $this->get(route('seguridad.logs'))->assertRedirect(route('login'));
    }

    public function test_logs_page_forbidden_for_moderador(): void
    {
        $this->actingAs($this->userWithRole('moderador'))
            ->get(route('seguridad.logs'))
            ->assertStatus(403);
    }

    public function test_logs_page_ok_for_admin(): void
    {
        SecurityLog::create(['event' => 'login', 'description' => 'Inicio de sesión correcto.']);

        $this->actingAs($this->userWithRole('admin'))
            ->get(route('seguridad.logs'))
            ->assertStatus(200)
            ->assertSee('Logs de seguridad');
    }

    // --- API ---

    public function test_api_security_logs_requires_auth(): void
    {
        $this->getJson('/api/security-logs')->assertStatus(401);
    }

    public function test_api_security_logs_forbidden_for_usuario(): void
    {
        $this->actingAs($this->userWithRole('usuario'), 'sanctum')
            ->getJson('/api/security-logs')
            ->assertStatus(403);
    }

    public function test_api_security_logs_ok_for_admin(): void
    {
        SecurityLog::create(['event' => 'login', 'description' => 'Inicio de sesión correcto.']);

        $this->actingAs($this->userWithRole('admin'), 'sanctum')
            ->getJson('/api/security-logs')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id', 'event', 'description', 'created_at']],
                'links',
                'meta',
            ]);
    }
}
