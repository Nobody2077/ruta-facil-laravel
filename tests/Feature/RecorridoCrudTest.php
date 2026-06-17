<?php

namespace Tests\Feature;

use App\Models\Parada;
use App\Models\Recorrido;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecorridoCrudTest extends TestCase
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
            'nombre'  => 'Linea Web',
            'origen'  => 'Ceja',
            'destino' => 'Rio Seco',
            'activo'  => '1',
            'paradas' => [
                ['orden' => 1, 'nombre' => 'Parada Uno'],
                ['orden' => 2, 'nombre' => 'Parada Dos'],
            ],
        ], $overrides);
    }

    // --- lectura publica ---

    public function test_index_returns_ok_publicly(): void
    {
        $this->get(route('recorridos.index'))->assertStatus(200);
    }

    public function test_show_returns_ok_publicly(): void
    {
        $recorrido = Recorrido::factory()->has(Parada::factory()->count(2), 'paradas')->create();

        $this->get(route('recorridos.show', $recorrido))->assertStatus(200);
    }

    // --- acceso protegido ---

    public function test_create_redirects_guest_to_login(): void
    {
        $this->get(route('recorridos.create'))->assertRedirect(route('login'));
    }

    public function test_create_returns_ok_for_moderador(): void
    {
        $this->actingAs($this->userWithRole('moderador'))
            ->get(route('recorridos.create'))
            ->assertStatus(200);
    }

    // --- store ---

    public function test_store_creates_recorrido_with_paradas_and_redirects(): void
    {
        $response = $this->actingAs($this->userWithRole('admin'))
            ->post(route('recorridos.store'), $this->recorridoData());

        $response->assertRedirect(route('recorridos.index'));

        $this->assertDatabaseHas('recorridos', ['nombre' => 'Linea Web']);
        $recorrido = Recorrido::where('nombre', 'Linea Web')->first();
        $this->assertDatabaseCount('paradas', 2);
        $this->assertDatabaseHas('paradas', ['recorrido_id' => $recorrido->id, 'nombre' => 'Parada Uno']);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->userWithRole('admin'))
            ->post(route('recorridos.store'), [])
            ->assertSessionHasErrors(['nombre', 'origen', 'destino', 'paradas']);
    }

    // --- update ---

    public function test_update_changes_recorrido_and_replaces_paradas(): void
    {
        $recorrido = Recorrido::factory()->has(Parada::factory()->count(3), 'paradas')->create();

        $response = $this->actingAs($this->userWithRole('admin'))
            ->put(route('recorridos.update', $recorrido), $this->recorridoData([
                'nombre'  => 'Linea Editada',
                'paradas' => [['orden' => 1, 'nombre' => 'Parada Final']],
            ]));

        $response->assertRedirect(route('recorridos.show', $recorrido));

        $this->assertDatabaseHas('recorridos', ['id' => $recorrido->id, 'nombre' => 'Linea Editada']);
        $this->assertDatabaseCount('paradas', 1);
        $this->assertDatabaseHas('paradas', ['recorrido_id' => $recorrido->id, 'nombre' => 'Parada Final']);
    }

    // --- destroy ---

    public function test_destroy_forbidden_for_moderador(): void
    {
        $recorrido = Recorrido::factory()->create();

        $this->actingAs($this->userWithRole('moderador'))
            ->delete(route('recorridos.destroy', $recorrido))
            ->assertStatus(403);

        $this->assertDatabaseHas('recorridos', ['id' => $recorrido->id]);
    }

    public function test_destroy_deletes_recorrido_and_cascades_for_admin(): void
    {
        $recorrido = Recorrido::factory()->has(Parada::factory()->count(2), 'paradas')->create();

        $this->actingAs($this->userWithRole('admin'))
            ->delete(route('recorridos.destroy', $recorrido))
            ->assertRedirect(route('recorridos.index'));

        $this->assertDatabaseMissing('recorridos', ['id' => $recorrido->id]);
        $this->assertDatabaseMissing('paradas', ['recorrido_id' => $recorrido->id]);
    }
}
