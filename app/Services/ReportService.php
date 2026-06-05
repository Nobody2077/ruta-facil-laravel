<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Opinion;
use App\Models\Project;

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
}
