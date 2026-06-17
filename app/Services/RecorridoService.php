<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Recorrido;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecorridoService
{
    public function paginated(int $perPage = 10): LengthAwarePaginator
    {
        return Recorrido::with('category')
            ->withCount('paradas')
            ->latest()
            ->paginate($perPage);
    }

    public function store(array $validated): Recorrido
    {
        return DB::transaction(function () use ($validated): Recorrido {
            $paradas = $validated['paradas'] ?? [];
            unset($validated['paradas']);

            $validated['codigo'] ??= 'R-'.strtoupper(Str::random(6));
            $validated['created_by'] = Auth::id();

            $recorrido = Recorrido::create($validated);
            $this->syncParadas($recorrido, $paradas);

            return $recorrido->load('paradas', 'category');
        });
    }

    public function update(Recorrido $recorrido, array $validated): Recorrido
    {
        return DB::transaction(function () use ($recorrido, $validated): Recorrido {
            $paradas = $validated['paradas'] ?? [];
            unset($validated['paradas']);

            $validated['codigo'] ??= $recorrido->codigo;

            $recorrido->update($validated);
            $this->syncParadas($recorrido, $paradas);

            return $recorrido->load('paradas', 'category');
        });
    }

    public function destroy(Recorrido $recorrido): void
    {
        $recorrido->delete();
    }

    public function loadForDetail(Recorrido $recorrido): Recorrido
    {
        return $recorrido->load('paradas', 'category', 'creator');
    }

    public function categoryList(): Collection
    {
        return Category::orderBy('name')->get();
    }

    /**
     * Reescribe el detalle (paradas) de la cabecera dentro de una transacción.
     *
     * @param  array<int, array<string, mixed>>  $paradas
     */
    private function syncParadas(Recorrido $recorrido, array $paradas): void
    {
        $recorrido->paradas()->delete();

        foreach (array_values($paradas) as $index => $parada) {
            $recorrido->paradas()->create([
                'orden' => $parada['orden'] ?? $index + 1,
                'nombre' => $parada['nombre'],
                'referencia' => $parada['referencia'] ?? null,
                'latitud' => $parada['latitud'] ?? null,
                'longitud' => $parada['longitud'] ?? null,
            ]);
        }
    }
}
