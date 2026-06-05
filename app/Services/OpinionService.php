<?php

namespace App\Services;

use App\Models\Opinion;
use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class OpinionService
{
    public function paginated(int $perPage = 8): LengthAwarePaginator
    {
        return Opinion::with(['project.category', 'user'])
            ->latest()
            ->paginate($perPage);
    }

    public function loadForDetail(Opinion $opinion): Opinion
    {
        return $opinion->load(['project.category', 'user', 'comments.user']);
    }

    public function projectList(): Collection
    {
        return Project::with('category')->orderBy('title')->get();
    }

    public function store(array $validated): Opinion
    {
        return Opinion::create([
            ...$validated,
            'user_id' => Auth::id(),
            'status'  => 'nuevo',
        ]);
    }

    public function update(Opinion $opinion, array $validated): Opinion
    {
        $opinion->update($validated);

        return $opinion;
    }

    public function destroy(Opinion $opinion): void
    {
        $opinion->delete();
    }
}
