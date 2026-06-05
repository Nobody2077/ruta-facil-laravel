<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    private function seedUsuarioRole(): Role
    {
        return Role::factory()->create(['slug' => 'usuario', 'name' => 'Usuario']);
    }

    public function test_register_creates_user_and_returns_token(): void
    {
        $this->seedUsuarioRole();

        $this->postJson('/api/auth/register', [
            'name'                  => 'Carlos Mamani',
            'email'                 => 'carlos@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'token',
                'user' => ['id', 'name', 'email', 'roles'],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'carlos@test.com']);
    }

    public function test_register_assigns_usuario_role(): void
    {
        $this->seedUsuarioRole();

        $this->postJson('/api/auth/register', [
            'name'                  => 'Ana Lopez',
            'email'                 => 'ana@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'ana@test.com')->first();
        $this->assertTrue($user->hasRole('usuario'));
    }

    public function test_register_validates_required_fields(): void
    {
        $this->postJson('/api/auth/register', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_register_validates_unique_email(): void
    {
        $this->seedUsuarioRole();
        User::factory()->create(['email' => 'taken@test.com']);

        $this->postJson('/api/auth/register', [
            'name'                  => 'Otro Usuario',
            'email'                 => 'taken@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_register_validates_password_confirmation(): void
    {
        $this->seedUsuarioRole();

        $this->postJson('/api/auth/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@test.com',
            'password'              => 'password123',
            'password_confirmation' => 'different456',
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_login_returns_token_with_valid_credentials(): void
    {
        User::factory()->create([
            'email'    => 'user@test.com',
            'password' => Hash::make('password123'),
        ]);

        $this->postJson('/api/auth/login', [
            'email'    => 'user@test.com',
            'password' => 'password123',
        ])
            ->assertStatus(200)
            ->assertJsonStructure(['message', 'token', 'user']);
    }

    public function test_login_returns_401_with_wrong_password(): void
    {
        User::factory()->create([
            'email'    => 'user@test.com',
            'password' => Hash::make('correcta'),
        ]);

        $this->postJson('/api/auth/login', [
            'email'    => 'user@test.com',
            'password' => 'incorrecta',
        ])->assertStatus(401);
    }

    public function test_login_returns_401_for_unknown_email(): void
    {
        $this->postJson('/api/auth/login', [
            'email'    => 'noexiste@test.com',
            'password' => 'cualquiera',
        ])->assertStatus(401);
    }

    public function test_logout_requires_authentication(): void
    {
        $this->postJson('/api/auth/logout')->assertStatus(401);
    }

    public function test_logout_revokes_token(): void
    {
        $user  = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/auth/logout')
            ->assertStatus(200)
            ->assertJsonPath('message', 'Sesion cerrada correctamente.');

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
