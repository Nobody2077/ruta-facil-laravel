<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function __construct(private ProjectService $service) {}

    public function index(): AnonymousResourceCollection
    {
        return ProjectResource::collection($this->service->paginated());
    }

    public function show(Project $project): ProjectResource
    {
        return new ProjectResource($project->load('category'));
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        $project = $this->service->store($request->validated());

        return (new ProjectResource($project->load('category')))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateProjectRequest $request, Project $project): ProjectResource
    {
        return new ProjectResource($this->service->update($project, $request->validated()));
    }

    public function destroy(Project $project): Response
    {
        $this->service->destroy($project);

        return response()->noContent();
    }
}
