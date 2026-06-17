<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelpTest extends TestCase
{
    use RefreshDatabase;

    public function test_help_page_is_public_and_renders(): void
    {
        $this->get(route('ayuda'))
            ->assertStatus(200)
            ->assertSee('Preguntas frecuentes')
            ->assertSee('¿Qué es Ruta Facil?');
    }

    public function test_help_page_shows_sections_by_role(): void
    {
        $response = $this->get(route('ayuda'))->assertStatus(200);

        $response->assertSee('Para usuarios');
        $response->assertSee('Para moderadores');
        $response->assertSee('Para administradores');
        $response->assertSee('Sobre la API');
    }
}
