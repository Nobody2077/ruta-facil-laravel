@csrf

@php
    $inputClass = 'w-full rounded-md border border-line bg-white px-3.5 py-2.5 text-ink focus:border-magenta focus:outline-none focus:ring-2 focus:ring-magenta/30';
    $labelClass = 'grid gap-1.5 font-extrabold text-ink';
@endphp

<div class="grid gap-4">
    <label class="{{ $labelClass }}">
        Proyecto relacionado
        <select name="project_id" class="{{ $inputClass }}">
            <option value="">Sin proyecto vinculado</option>
            @foreach ($projects ?? [] as $project)
                <option value="{{ $project->id }}" @selected((int) old('project_id', $opinion->project_id ?? 0) === $project->id)>
                    {{ $project->title }} @if ($project->category) - {{ $project->category->name }} @endif
                </option>
            @endforeach
        </select>
        @error('project_id') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
    </label>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="{{ $labelClass }}">
            Nombre
            <input type="text" name="name" value="{{ old('name', $opinion->name ?? '') }}" placeholder="Tu nombre" class="{{ $inputClass }}" required>
            @error('name') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
        </label>

        <label class="{{ $labelClass }}">
            Zona o ruta que usas
            <input type="text" name="route" value="{{ old('route', $opinion->route ?? '') }}" placeholder="Ej. Ceja - Rio Seco" class="{{ $inputClass }}" required>
            @error('route') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
        </label>
    </div>

    <label class="{{ $labelClass }}">
        Opinion o reporte
        <textarea name="message" rows="5" placeholder="Cuentanos que ruta falta, que problema viste o que funcion necesitas." class="{{ $inputClass }}" required>{{ old('message', $opinion->message ?? '') }}</textarea>
        @error('message') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
    </label>

    @if (isset($opinion) && $opinion->exists)
        <label class="{{ $labelClass }}">
            Estado
            <select name="status" class="{{ $inputClass }}" required>
                @foreach (['nuevo' => 'Nuevo', 'revisado' => 'Revisado', 'archivado' => 'Archivado'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $opinion->status) === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
        </label>
    @else
        <input type="hidden" name="status" value="nuevo">
    @endif
</div>
