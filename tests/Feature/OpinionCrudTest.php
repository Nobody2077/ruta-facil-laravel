<?php

namespace Tests\Feature;

use App\Models\Opinion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpinionCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_ok(): void
    {
        $this->get(route('opinions.index'))->assertStatus(200);
    }

    public function test_create_returns_ok(): void
    {
        $this->get(route('opinions.create'))->assertStatus(200);
    }

    public function test_store_creates_opinion_and_redirects(): void
    {
        $response = $this->post(route('opinions.store'), [
            'name'    => 'Juan Quispe',
            'route'   => 'Ceja - Rio Seco',
            'message' => 'El minibus tarda mucho en llegar.',
        ]);

        $response->assertRedirect(route('opinions.index'));
        $this->assertDatabaseHas('opinions', [
            'name'   => 'Juan Quispe',
            'status' => 'nuevo',
        ]);
    }

    public function test_store_always_forces_status_nuevo(): void
    {
        $this->post(route('opinions.store'), [
            'name'    => 'Test Usuario',
            'route'   => 'Villa Adela - UPEA',
            'message' => 'Reporte de prueba.',
            'status'  => 'archivado',
        ]);

        $this->assertDatabaseHas('opinions', [
            'name'   => 'Test Usuario',
            'status' => 'nuevo',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->post(route('opinions.store'), [])
            ->assertSessionHasErrors(['name', 'route', 'message']);
    }

    public function test_store_validates_max_length(): void
    {
        $this->post(route('opinions.store'), [
            'name'    => str_repeat('a', 121),
            'route'   => str_repeat('b', 161),
            'message' => 'Mensaje valido.',
        ])->assertSessionHasErrors(['name', 'route']);
    }

    public function test_show_returns_ok(): void
    {
        $opinion = Opinion::factory()->create();

        $this->get(route('opinions.show', $opinion))->assertStatus(200);
    }

    public function test_edit_returns_ok(): void
    {
        $opinion = Opinion::factory()->create();

        $this->get(route('opinions.edit', $opinion))->assertStatus(200);
    }

    public function test_update_changes_opinion_and_redirects(): void
    {
        $opinion = Opinion::factory()->create();

        $response = $this->put(route('opinions.update', $opinion), [
            'name'    => 'Nombre Actualizado',
            'route'   => 'Senkata - Ventilla',
            'message' => 'Mensaje actualizado correctamente.',
            'status'  => 'revisado',
        ]);

        $response->assertRedirect(route('opinions.show', $opinion));
        $this->assertDatabaseHas('opinions', [
            'id'     => $opinion->id,
            'name'   => 'Nombre Actualizado',
            'status' => 'revisado',
        ]);
    }

    public function test_update_validates_required_fields(): void
    {
        $opinion = Opinion::factory()->create();

        $this->put(route('opinions.update', $opinion), [])
            ->assertSessionHasErrors(['name', 'route', 'message', 'status']);
    }

    public function test_destroy_deletes_opinion_and_redirects(): void
    {
        $opinion = Opinion::factory()->create();

        $this->delete(route('opinions.destroy', $opinion))
            ->assertRedirect(route('opinions.index'));

        $this->assertDatabaseMissing('opinions', ['id' => $opinion->id]);
    }
}
