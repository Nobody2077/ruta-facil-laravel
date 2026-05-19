<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    public function index()
    {
        $opinions = Opinion::latest()->paginate(8);

        return view('opinions.index', compact('opinions'));
    }

    public function create()
    {
        return view('opinions.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateOpinion($request);

        Opinion::create($data);

        return redirect()
            ->route('opinions.index')
            ->with('status', 'Opinion registrada correctamente.');
    }

    public function show(Opinion $opinion)
    {
        return view('opinions.show', compact('opinion'));
    }

    public function edit(Opinion $opinion)
    {
        return view('opinions.edit', compact('opinion'));
    }

    public function update(Request $request, Opinion $opinion)
    {
        $data = $this->validateOpinion($request);

        $opinion->update($data);

        return redirect()
            ->route('opinions.show', $opinion)
            ->with('status', 'Opinion actualizada correctamente.');
    }

    public function destroy(Opinion $opinion)
    {
        $opinion->delete();

        return redirect()
            ->route('opinions.index')
            ->with('status', 'Opinion eliminada correctamente.');
    }

    private function validateOpinion(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'route' => ['required', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:2000'],
            'status' => ['required', 'string', 'in:nuevo,revisado,archivado'],
        ]);
    }
}
