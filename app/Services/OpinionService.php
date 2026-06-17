<?php

namespace App\Services;

use App\Models\Opinion;
use App\Models\Project;
use App\Models\User;
use App\Notifications\NuevaOpinionNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

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
        $opinion = Opinion::create([
            ...$validated,
            'user_id' => Auth::id(),
            'status'  => 'nuevo',
        ]);

        $this->notifyStaff($opinion);

        return $opinion;
    }

    /**
     * Alarma interna: avisa a admin y moderadores cuando entra una opinión nueva.
     * Excluye al autor si la creó estando autenticado.
     */
    private function notifyStaff(Opinion $opinion): void
    {
        $recipients = User::whereHas('roles', fn ($q) => $q->whereIn('slug', ['admin', 'moderador']))
            ->when(Auth::id(), fn ($q, $id) => $q->where('id', '!=', $id))
            ->get();

        Notification::send($recipients, new NuevaOpinionNotification($opinion));
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
