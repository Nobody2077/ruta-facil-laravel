<?php

namespace Tests\Feature;

use App\Models\Opinion;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpinionApiTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug): User
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => $slug]);
        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        return $user;
    }

    public function test_index_returns_paginated_json(): void
    {
        Opinion::factory()->count(3)->create();

        $this->getJson('/api/opinions')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id', 'name', 'route', 'message', 'status', 'created_at']],
                'links',
                'meta',
            ]);
    }

    public function test_show_returns_opinion_json(): void
    {
        $opinion = Opinion::factory()->create();

        $this->getJson("/api/opinions/{$opinion->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $opinion->id)
            ->assertJsonPath('data.name', $opinion->name);
    }

    public function test_show_returns_404_for_missing_opinion(): void
    {
        $this->getJson('/api/opinions/9999')->assertStatus(404);
    }

    public function test_store_requires_authentication(): void
    {
        $this->postJson('/api/opinions', [
            'name'    => 'Test',
            'route'   => 'Ceja - Rio Seco',
            'message' => 'Mensaje de prueba.',
        ])->assertStatus(401);
    }

    public function test_store_creates_opinion_when_authenticated(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/opinions', [
                'name'    => 'Maria Quispe',
                'route'   => 'Villa Adela - UPEA',
                'message' => 'Reporte ciudadano de prueba.',
            ])
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Maria Quispe')
            ->assertJsonPath('data.status', 'nuevo');

        $this->assertDatabaseHas('opinions', ['name' => 'Maria Quispe']);
    }

    public function test_store_returns_422_on_validation_error(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/opinions', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'route', 'message']);
    }

    public function test_update_requires_authentication(): void
    {
        $opinion = Opinion::factory()->create();

        $this->putJson("/api/opinions/{$opinion->id}", [
            'name' => 'x', 'route' => 'x', 'message' => 'x', 'status' => 'nuevo',
        ])->assertStatus(401);
    }

    public function test_update_returns_403_for_usuario_role(): void
    {
        $user    = $this->userWithRole('usuario');
        $opinion = Opinion::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/opinions/{$opinion->id}", [
                'name'    => 'Editado',
                'route'   => 'Ceja - Rio Seco',
                'message' => 'Mensaje editado.',
                'status'  => 'revisado',
            ])->assertStatus(403);
    }

    public function test_update_succeeds_for_moderador(): void
    {
        $user    = $this->userWithRole('moderador');
        $opinion = Opinion::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/opinions/{$opinion->id}", [
                'name'    => 'Nombre Editado',
                'route'   => 'Ceja - Rio Seco',
                'message' => 'Mensaje editado.',
                'status'  => 'revisado',
            ])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'revisado');
    }

    public function test_update_succeeds_for_admin(): void
    {
        $user    = $this->userWithRole('admin');
        $opinion = Opinion::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/opinions/{$opinion->id}", [
                'name'    => 'Admin Edit',
                'route'   => 'Ceja - Rio Seco',
                'message' => 'Editado por admin.',
                'status'  => 'archivado',
            ])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'archivado');
    }

    public function test_destroy_requires_authentication(): void
    {
        $opinion = Opinion::factory()->create();

        $this->deleteJson("/api/opinions/{$opinion->id}")->assertStatus(401);
    }

    public function test_destroy_returns_403_for_moderador(): void
    {
        $user    = $this->userWithRole('moderador');
        $opinion = Opinion::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/opinions/{$opinion->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('opinions', ['id' => $opinion->id]);
    }

    public function test_destroy_succeeds_for_admin(): void
    {
        $user    = $this->userWithRole('admin');
        $opinion = Opinion::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/opinions/{$opinion->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('opinions', ['id' => $opinion->id]);
    }
}
