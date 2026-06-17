<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Opinion;
use App\Models\Parada;
use App\Models\Project;
use App\Models\Recorrido;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportApiTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $slug): User
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['slug' => $slug]);
        $user->roles()->attach($role->id, ['assigned_at' => now()]);

        return $user;
    }

    public function test_summary_returns_401_without_auth(): void
    {
        $this->getJson('/api/reports/summary')->assertStatus(401);
    }

    public function test_summary_returns_403_for_usuario_role(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/reports/summary')
            ->assertStatus(403);
    }

    public function test_summary_returns_200_for_admin(): void
    {
        $user = $this->userWithRole('admin');

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/reports/summary')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_opinions',
                    'opinions_by_status',
                    'opinions_by_project',
                    'projects_by_category',
                    'total_comments',
                ],
            ]);
    }

    public function test_summary_returns_200_for_moderador(): void
    {
        $user = $this->userWithRole('moderador');

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/reports/summary')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_opinions',
                    'opinions_by_status',
                    'opinions_by_project',
                    'projects_by_category',
                    'total_comments',
                ],
            ]);
    }

    public function test_summary_returns_correct_counts(): void
    {
        $user     = $this->userWithRole('admin');
        $category = Category::factory()->create();
        $project  = Project::factory()->create(['category_id' => $category->id]);

        Opinion::factory()->count(3)->create(['project_id' => $project->id, 'status' => 'nuevo']);
        Opinion::factory()->count(2)->create(['project_id' => $project->id, 'status' => 'revisado']);
        Comment::factory()->count(4)->create([
            'commentable_type' => Opinion::class,
            'commentable_id'   => Opinion::first()->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/reports/summary')
            ->assertStatus(200);

        $this->assertEquals(5, $response->json('data.total_opinions'));
        $this->assertEquals(4, $response->json('data.total_comments'));
        $this->assertEquals(3, $response->json('data.opinions_by_status.nuevo'));
        $this->assertEquals(2, $response->json('data.opinions_by_status.revisado'));
    }

    // --- reporte cabecera-detalle de recorridos ---

    public function test_recorridos_report_returns_401_without_auth(): void
    {
        $this->getJson('/api/reports/recorridos')->assertStatus(401);
    }

    public function test_recorridos_report_returns_403_for_usuario(): void
    {
        $user = $this->userWithRole('usuario');

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/reports/recorridos')
            ->assertStatus(403);
    }

    public function test_recorridos_report_returns_structure_and_counts_for_admin(): void
    {
        $user = $this->userWithRole('admin');
        Recorrido::factory()->has(Parada::factory()->count(3), 'paradas')->create();
        Recorrido::factory()->has(Parada::factory()->count(2), 'paradas')->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/reports/recorridos')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'total_recorridos',
                    'total_paradas',
                    'recorridos_activos',
                    'promedio_paradas',
                    'recorridos_by_category',
                    'detalle' => [['codigo', 'nombre', 'origen', 'destino', 'paradas' => [['orden', 'nombre']]]],
                ],
            ]);

        $this->assertEquals(2, $response->json('data.total_recorridos'));
        $this->assertEquals(5, $response->json('data.total_paradas'));
    }
}
