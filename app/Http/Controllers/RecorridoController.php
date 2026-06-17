<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRecorridoRequest;
use App\Http\Requests\UpdateRecorridoRequest;
use App\Models\Recorrido;
use App\Services\RecorridoService;

class RecorridoController extends Controller
{
    public function __construct(private RecorridoService $service) {}

    public function index()
    {
        $recorridos = $this->service->paginated();

        return view('recorridos.index', compact('recorridos'));
    }

    public function create()
    {
        $categories = $this->service->categoryList();

        return view('recorridos.create', compact('categories'));
    }

    public function store(StoreRecorridoRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('recorridos.index')
            ->with('status', 'Recorrido registrado correctamente.');
    }

    public function show(Recorrido $recorrido)
    {
        $recorrido = $this->service->loadForDetail($recorrido);

        return view('recorridos.show', compact('recorrido'));
    }

    public function edit(Recorrido $recorrido)
    {
        $recorrido = $this->service->loadForDetail($recorrido);
        $categories = $this->service->categoryList();

        return view('recorridos.edit', compact('recorrido', 'categories'));
    }

    public function update(UpdateRecorridoRequest $request, Recorrido $recorrido)
    {
        $this->service->update($recorrido, $request->validated());

        return redirect()
            ->route('recorridos.show', $recorrido)
            ->with('status', 'Recorrido actualizado correctamente.');
    }

    public function destroy(Recorrido $recorrido)
    {
        $this->service->destroy($recorrido);

        return redirect()
            ->route('recorridos.index')
            ->with('status', 'Recorrido eliminado correctamente.');
    }
}
