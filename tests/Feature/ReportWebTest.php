<?php

namespace Tests\Feature;

use App\Models\Parada;
use App\Models\Recorrido;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportWebTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug): User
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => $slug]);
        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        return $user;
    }

    // --- acceso ---

    public function test_resumen_redirects_guest(): void
    {
        $this->get(route('reportes.resumen'))->assertRedirect();
    }

    public function test_resumen_forbidden_for_usuario(): void
    {
        $this->actingAs($this->userWithRole('usuario'))
            ->get(route('reportes.resumen'))
            ->assertStatus(403);
    }

    public function test_resumen_ok_for_admin(): void
    {
        $this->actingAs($this->userWithRole('admin'))
            ->get(route('reportes.resumen'))
            ->assertStatus(200);
    }

    public function test_recorridos_report_ok_for_moderador(): void
    {
        Recorrido::factory()->has(Parada::factory()->count(2), 'paradas')->create();

        $this->actingAs($this->userWithRole('moderador'))
            ->get(route('reportes.recorridos'))
            ->assertStatus(200)
            ->assertSee('Reporte de recorridos');
    }

    // --- exportacion PDF ---

    public function test_resumen_pdf_downloads_for_admin(): void
    {
        $response = $this->actingAs($this->userWithRole('admin'))
            ->get(route('reportes.resumen.pdf'));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }

    public function test_recorridos_pdf_downloads_for_admin(): void
    {
        Recorrido::factory()->has(Parada::factory()->count(3), 'paradas')->create();

        $response = $this->actingAs($this->userWithRole('admin'))
            ->get(route('reportes.recorridos.pdf'));

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
    }

    public function test_recorridos_pdf_forbidden_for_usuario(): void
    {
        $this->actingAs($this->userWithRole('usuario'))
            ->get(route('reportes.recorridos.pdf'))
            ->assertStatus(403);
    }
}
