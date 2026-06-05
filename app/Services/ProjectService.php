<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProjectService
{
    public function paginated(int $perPage = 10): LengthAwarePaginator
    {
        return Project::with('category')->latest()->paginate($perPage);
    }

    public function store(array $validated): Project
    {
        $validated['slug']       ??= Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['created_by'] = Auth::id();

        return Project::create($validated);
    }

    public function update(Project $project, array $validated): Project
    {
        $validated['slug'] ??= Str::slug($validated['title']) . '-' . Str::random(4);

        $project->update($validated);

        return $project->load('category');
    }

    public function destroy(Project $project): void
    {
        $project->delete();
    }
}
