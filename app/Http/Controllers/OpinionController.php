<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpinionRequest;
use App\Http\Requests\UpdateOpinionRequest;
use App\Models\Opinion;
use App\Services\OpinionService;

class OpinionController extends Controller
{
    public function __construct(private OpinionService $service) {}

    public function index()
    {
        $opinions = $this->service->paginated();

        return view('opinions.index', compact('opinions'));
    }

    public function create()
    {
        $projects = $this->service->projectList();

        return view('opinions.create', compact('projects'));
    }

    public function store(StoreOpinionRequest $request)
    {
        $this->service->store($request->validated());

        return redirect()
            ->route('opinions.index')
            ->with('status', 'Opinion registrada correctamente.');
    }

    public function show(Opinion $opinion)
    {
        $opinion = $this->service->loadForDetail($opinion);

        return view('opinions.show', compact('opinion'));
    }

    public function edit(Opinion $opinion)
    {
        $projects = $this->service->projectList();

        return view('opinions.edit', compact('opinion', 'projects'));
    }

    public function update(UpdateOpinionRequest $request, Opinion $opinion)
    {
        $this->service->update($opinion, $request->validated());

        return redirect()
            ->route('opinions.show', $opinion)
            ->with('status', 'Opinion actualizada correctamente.');
    }

    public function destroy(Opinion $opinion)
    {
        $this->service->destroy($opinion);

        return redirect()
            ->route('opinions.index')
            ->with('status', 'Opinion eliminada correctamente.');
    }
}
