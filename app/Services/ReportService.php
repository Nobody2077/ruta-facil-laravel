<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Opinion;
use App\Models\Parada;
use App\Models\Project;
use App\Models\Recorrido;

class ReportService
{
    public function summary(): array
    {
        return [
            'total_opinions'       => Opinion::count(),
            'opinions_by_status'   => Opinion::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'opinions_by_project'  => Project::withCount('opinions')
                ->orderByDesc('opinions_count')
                ->get(['id', 'title'])
                ->map(fn ($p) => ['project' => $p->title, 'total' => $p->opinions_count]),
            'projects_by_category' => Category::withCount('projects')
                ->get(['id', 'name'])
                ->map(fn ($c) => ['category' => $c->name, 'total' => $c->projects_count]),
            'total_comments'       => Comment::count(),
        ];
    }

    /**
     * Reporte cabecera-detalle: cada recorrido con sus paradas, más estadísticas agregadas.
     */
    public function recorridos(): array
    {
        return [
            'generado_en'           => now()->format('d/m/Y H:i'),
            'total_recorridos'      => Recorrido::count(),
            'total_paradas'         => Parada::count(),
            'recorridos_activos'    => Recorrido::where('activo', true)->count(),
            'recorridos_inactivos'  => Recorrido::where('activo', false)->count(),
            'promedio_paradas'      => round((float) Recorrido::withCount('paradas')->get()->avg('paradas_count'), 1),
            'recorridos_by_category' => Category::withCount('recorridos')
                ->orderByDesc('recorridos_count')
                ->get(['id', 'name'])
                ->map(fn ($c) => ['category' => $c->name, 'total' => $c->recorridos_count]),
            'detalle'               => Recorrido::with(['category', 'paradas'])
                ->orderBy('codigo')
                ->get()
                ->map(fn (Recorrido $r) => [
                    'codigo'    => $r->codigo,
                    'nombre'    => $r->nombre,
                    'origen'    => $r->origen,
                    'destino'   => $r->destino,
                    'categoria' => $r->category?->name ?? 'Sin categoría',
                    'tarifa_bs' => $r->tarifa_bs,
                    'activo'    => $r->activo,
                    'paradas'   => $r->paradas->map(fn (Parada $p) => [
                        'orden'      => $p->orden,
                        'nombre'     => $p->nombre,
                        'referencia' => $p->referencia,
                    ])->values(),
                ]),
        ];
    }
}
