<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecorridoRequest;
use App\Http\Requests\UpdateRecorridoRequest;
use App\Http\Resources\RecorridoResource;
use App\Models\Recorrido;
use App\Services\RecorridoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class RecorridoController extends Controller
{
    public function __construct(private RecorridoService $service) {}

    public function index(): AnonymousResourceCollection
    {
        return RecorridoResource::collection($this->service->paginated());
    }

    public function show(Recorrido $recorrido): RecorridoResource
    {
        return new RecorridoResource($recorrido->load('category', 'paradas'));
    }

    public function store(StoreRecorridoRequest $request): JsonResponse
    {
        $recorrido = $this->service->store($request->validated());

        return (new RecorridoResource($recorrido))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateRecorridoRequest $request, Recorrido $recorrido): RecorridoResource
    {
        return new RecorridoResource($this->service->update($recorrido, $request->validated()));
    }

    public function destroy(Recorrido $recorrido): Response
    {
        $this->service->destroy($recorrido);

        return response()->noContent();
    }
}
