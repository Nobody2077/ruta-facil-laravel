<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug): User
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => $slug]);
        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        return $user;
    }

    private function projectData(int $categoryId, array $overrides = []): array
    {
        return array_merge([
            'category_id' => $categoryId,
            'title'       => 'Proyecto de Prueba',
            'origin'      => 'Ceja',
            'destination' => 'Rio Seco',
            'description' => 'Descripción del proyecto de prueba.',
            'status'      => 'planificado',
        ], $overrides);
    }

    // --- index ---

    public function test_index_returns_paginated_projects_publicly(): void
    {
        Project::factory()->count(3)->create();

        $this->getJson('/api/projects')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'  => [['id', 'title', 'origin', 'destination', 'status']],
                'links',
                'meta',
            ]);
    }

    // --- show ---

    public function test_show_returns_project(): void
    {
        $project = Project::factory()->create();

        $this->getJson("/api/projects/{$project->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $project->id)
            ->assertJsonPath('data.title', $project->title);
    }

    public function test_show_returns_404_for_missing_project(): void
    {
        $this->getJson('/api/projects/9999')->assertStatus(404);
    }

    // --- store ---

    public function test_store_requires_auth(): void
    {
        $category = Category::factory()->create();

        $this->postJson('/api/projects', $this->projectData($category->id))
            ->assertStatus(401);
    }

    public function test_store_creates_project_when_authenticated(): void
    {
        $user     = $this->userWithRole('usuario');
        $category = Category::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/projects', $this->projectData($category->id, ['title' => 'Nuevo Proyecto']))
            ->assertStatus(201)
            ->assertJsonPath('data.title', 'Nuevo Proyecto')
            ->assertJsonPath('data.status', 'planificado');

        $this->assertDatabaseHas('projects', ['title' => 'Nuevo Proyecto']);
    }

    public function test_store_returns_422_on_validation_error(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/projects', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['category_id', 'title', 'origin', 'destination', 'description']);
    }

    public function test_store_returns_422_for_invalid_status(): void
    {
        $user     = $this->userWithRole('usuario');
        $category = Category::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/projects', $this->projectData($category->id, ['status' => 'invalido']))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    // --- update ---

    public function test_update_requires_auth(): void
    {
        $project  = Project::factory()->create();
        $category = Category::factory()->create();

        $this->putJson("/api/projects/{$project->id}", $this->projectData($category->id))
            ->assertStatus(401);
    }

    public function test_update_returns_403_for_usuario(): void
    {
        $user     = $this->userWithRole('usuario');
        $project  = Project::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/projects/{$project->id}", $this->projectData($category->id))
            ->assertStatus(403);
    }

    public function test_update_succeeds_for_admin(): void
    {
        $user     = $this->userWithRole('admin');
        $project  = Project::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/projects/{$project->id}", $this->projectData($category->id, [
                'title'  => 'Proyecto Actualizado',
                'status' => 'activo',
            ]))
            ->assertStatus(200)
            ->assertJsonPath('data.title', 'Proyecto Actualizado')
            ->assertJsonPath('data.status', 'activo');
    }

    public function test_update_succeeds_for_moderador(): void
    {
        $user     = $this->userWithRole('moderador');
        $project  = Project::factory()->create();
        $category = Category::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/projects/{$project->id}", $this->projectData($category->id, [
                'title'  => 'Proyecto Mod',
                'status' => 'pausado',
            ]))
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'pausado');
    }

    // --- destroy ---

    public function test_destroy_requires_auth(): void
    {
        $project = Project::factory()->create();

        $this->deleteJson("/api/projects/{$project->id}")->assertStatus(401);
    }

    public function test_destroy_returns_403_for_moderador(): void
    {
        $user    = $this->userWithRole('moderador');
        $project = Project::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/projects/{$project->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_destroy_succeeds_for_admin(): void
    {
        $user    = $this->userWithRole('admin');
        $project = Project::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/projects/{$project->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
