<?php

namespace Tests\Feature;

use App\Models\Parada;
use App\Models\Recorrido;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecorridoApiTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug): User
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => $slug]);
        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        return $user;
    }

    private function recorridoData(array $overrides = []): array
    {
        return array_merge([
            'nombre'  => 'Linea de Prueba',
            'origen'  => 'Ceja',
            'destino' => 'Rio Seco',
            'tarifa_bs' => 2.50,
            'paradas' => [
                ['orden' => 1, 'nombre' => 'Parada A', 'referencia' => 'Esquina norte'],
                ['orden' => 2, 'nombre' => 'Parada B'],
            ],
        ], $overrides);
    }

    // --- index ---

    public function test_index_returns_paginated_recorridos_publicly(): void
    {
        Recorrido::factory()->count(3)->has(Parada::factory()->count(2), 'paradas')->create();

        $this->getJson('/api/recorridos')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'  => [['id', 'nombre', 'codigo', 'origen', 'destino', 'paradas_count']],
                'links',
                'meta',
            ]);
    }

    // --- show ---

    public function test_show_returns_recorrido_with_paradas(): void
    {
        $recorrido = Recorrido::factory()->has(Parada::factory()->count(3), 'paradas')->create();

        $this->getJson("/api/recorridos/{$recorrido->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $recorrido->id)
            ->assertJsonCount(3, 'data.paradas');
    }

    public function test_show_returns_404_for_missing_recorrido(): void
    {
        $this->getJson('/api/recorridos/9999')->assertStatus(404);
    }

    // --- store ---

    public function test_store_requires_auth(): void
    {
        $this->postJson('/api/recorridos', $this->recorridoData())
            ->assertStatus(401);
    }

    public function test_store_creates_recorrido_with_paradas_when_authenticated(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/recorridos', $this->recorridoData(['nombre' => 'Nueva Linea']))
            ->assertStatus(201)
            ->assertJsonPath('data.nombre', 'Nueva Linea')
            ->assertJsonCount(2, 'data.paradas');

        $this->assertDatabaseHas('recorridos', ['nombre' => 'Nueva Linea']);
        $recorrido = Recorrido::where('nombre', 'Nueva Linea')->first();
        $this->assertDatabaseCount('paradas', 2);
        $this->assertDatabaseHas('paradas', ['recorrido_id' => $recorrido->id, 'nombre' => 'Parada A']);
    }

    public function test_store_returns_422_when_required_fields_missing(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/recorridos', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nombre', 'origen', 'destino', 'paradas']);
    }

    public function test_store_returns_422_when_paradas_empty(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/recorridos', $this->recorridoData(['paradas' => []]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['paradas']);
    }

    public function test_store_returns_422_when_parada_nombre_missing(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/recorridos', $this->recorridoData([
                'paradas' => [['orden' => 1]],
            ]))
            ->assertStatus(422)
            ->assertJsonValidationErrors(['paradas.0.nombre']);
    }

    // --- update ---

    public function test_update_requires_auth(): void
    {
        $recorrido = Recorrido::factory()->create();

        $this->putJson("/api/recorridos/{$recorrido->id}", $this->recorridoData())
            ->assertStatus(401);
    }

    public function test_update_returns_403_for_usuario(): void
    {
        $user = $this->userWithRole('usuario');
        $recorrido = Recorrido::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/recorridos/{$recorrido->id}", $this->recorridoData())
            ->assertStatus(403);
    }

    public function test_update_succeeds_for_admin_and_replaces_paradas(): void
    {
        $user = $this->userWithRole('admin');
        $recorrido = Recorrido::factory()->has(Parada::factory()->count(4), 'paradas')->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/recorridos/{$recorrido->id}", $this->recorridoData([
                'nombre'  => 'Linea Actualizada',
                'paradas' => [['orden' => 1, 'nombre' => 'Unica Parada']],
            ]))
            ->assertStatus(200)
            ->assertJsonPath('data.nombre', 'Linea Actualizada')
            ->assertJsonCount(1, 'data.paradas');

        // El detalle se reescribe: solo debe quedar 1 parada.
        $this->assertDatabaseCount('paradas', 1);
        $this->assertDatabaseHas('paradas', ['recorrido_id' => $recorrido->id, 'nombre' => 'Unica Parada']);
    }

    public function test_update_succeeds_for_moderador(): void
    {
        $user = $this->userWithRole('moderador');
        $recorrido = Recorrido::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/recorridos/{$recorrido->id}", $this->recorridoData(['nombre' => 'Linea Mod']))
            ->assertStatus(200)
            ->assertJsonPath('data.nombre', 'Linea Mod');
    }

    // --- destroy ---

    public function test_destroy_requires_auth(): void
    {
        $recorrido = Recorrido::factory()->create();

        $this->deleteJson("/api/recorridos/{$recorrido->id}")->assertStatus(401);
    }

    public function test_destroy_returns_403_for_moderador(): void
    {
        $user = $this->userWithRole('moderador');
        $recorrido = Recorrido::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/recorridos/{$recorrido->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('recorridos', ['id' => $recorrido->id]);
    }

    public function test_destroy_succeeds_for_admin_and_cascades_paradas(): void
    {
        $user = $this->userWithRole('admin');
        $recorrido = Recorrido::factory()->has(Parada::factory()->count(3), 'paradas')->create();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/recorridos/{$recorrido->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('recorridos', ['id' => $recorrido->id]);
        $this->assertDatabaseMissing('paradas', ['recorrido_id' => $recorrido->id]);
    }
}
