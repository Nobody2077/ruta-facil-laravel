<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug): User
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => $slug]);
        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        return $user;
    }

    private function categoryData(array $overrides = []): array
    {
        return array_merge([
            'name'        => 'Categoría de Prueba',
            'color'       => '#ff5733',
            'description' => 'Descripción de prueba.',
        ], $overrides);
    }

    // --- index ---

    public function test_index_returns_all_categories_publicly(): void
    {
        Category::factory()->count(3)->create();

        $this->getJson('/api/categories')
            ->assertStatus(200)
            ->assertJsonStructure(['data' => [['id', 'name', 'color']]]);
    }

    // --- show ---

    public function test_show_returns_category(): void
    {
        $category = Category::factory()->create();

        $this->getJson("/api/categories/{$category->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $category->id)
            ->assertJsonPath('data.name', $category->name);
    }

    public function test_show_returns_404_for_missing_category(): void
    {
        $this->getJson('/api/categories/9999')->assertStatus(404);
    }

    // --- store ---

    public function test_store_requires_auth(): void
    {
        $this->postJson('/api/categories', $this->categoryData())->assertStatus(401);
    }

    public function test_store_returns_403_for_usuario(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/categories', $this->categoryData())
            ->assertStatus(403);
    }

    public function test_store_creates_category_for_admin(): void
    {
        $user = $this->userWithRole('admin');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/categories', $this->categoryData(['name' => 'Nueva Categoría Admin']))
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Nueva Categoría Admin');

        $this->assertDatabaseHas('categories', ['name' => 'Nueva Categoría Admin']);
    }

    public function test_store_creates_category_for_moderador(): void
    {
        $user = $this->userWithRole('moderador');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/categories', $this->categoryData(['name' => 'Nueva Categoría Mod']))
            ->assertStatus(201)
            ->assertJsonPath('data.name', 'Nueva Categoría Mod');
    }

    public function test_store_returns_422_on_validation_error(): void
    {
        $user = $this->userWithRole('admin');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/categories', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    // --- update ---

    public function test_update_requires_auth(): void
    {
        $category = Category::factory()->create();

        $this->putJson("/api/categories/{$category->id}", $this->categoryData())
            ->assertStatus(401);
    }

    public function test_update_returns_403_for_usuario(): void
    {
        $user     = $this->userWithRole('usuario');
        $category = Category::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/categories/{$category->id}", $this->categoryData())
            ->assertStatus(403);
    }

    public function test_update_succeeds_for_admin(): void
    {
        $user     = $this->userWithRole('admin');
        $category = Category::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/categories/{$category->id}", $this->categoryData(['name' => 'Nombre Actualizado']))
            ->assertStatus(200)
            ->assertJsonPath('data.name', 'Nombre Actualizado');
    }

    // --- destroy ---

    public function test_destroy_requires_auth(): void
    {
        $category = Category::factory()->create();

        $this->deleteJson("/api/categories/{$category->id}")->assertStatus(401);
    }

    public function test_destroy_returns_403_for_moderador(): void
    {
        $user     = $this->userWithRole('moderador');
        $category = Category::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/categories/{$category->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_destroy_succeeds_for_admin(): void
    {
        $user     = $this->userWithRole('admin');
        $category = Category::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/categories/{$category->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
