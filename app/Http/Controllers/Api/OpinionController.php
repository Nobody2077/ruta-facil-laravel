<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOpinionRequest;
use App\Http\Requests\UpdateOpinionRequest;
use App\Http\Resources\OpinionResource;
use App\Models\Opinion;
use App\Services\OpinionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class OpinionController extends Controller
{
    public function __construct(private OpinionService $service) {}

    public function index(): AnonymousResourceCollection
    {
        return OpinionResource::collection($this->service->paginated());
    }

    public function store(StoreOpinionRequest $request): JsonResponse
    {
        $opinion = $this->service->store($request->validated());

        return (new OpinionResource($opinion))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Opinion $opinion): OpinionResource
    {
        return new OpinionResource($this->service->loadForDetail($opinion));
    }

    public function update(UpdateOpinionRequest $request, Opinion $opinion): OpinionResource
    {
        return new OpinionResource($this->service->update($opinion, $request->validated()));
    }

    public function destroy(Opinion $opinion): Response
    {
        $this->service->destroy($opinion);

        return response()->noContent();
    }
}
