@csrf

<label>
  Proyecto relacionado
  <select name="project_id">
    <option value="">Sin proyecto vinculado</option>
    @foreach ($projects ?? [] as $project)
      <option value="{{ $project->id }}" @selected((int) old('project_id', $opinion->project_id ?? 0) === $project->id)>
        {{ $project->title }} @if ($project->category) - {{ $project->category->name }} @endif
      </option>
    @endforeach
  </select>
  @error('project_id') <span class="error-text">{{ $message }}</span> @enderror
</label>

<label>
  Nombre
  <input type="text" name="name" value="{{ old('name', $opinion->name ?? '') }}" placeholder="Tu nombre" required>
  @error('name') <span class="error-text">{{ $message }}</span> @enderror
</label>

<label>
  Zona o ruta que usas
  <input type="text" name="route" value="{{ old('route', $opinion->route ?? '') }}" placeholder="Ej. Ceja - Rio Seco" required>
  @error('route') <span class="error-text">{{ $message }}</span> @enderror
</label>

<label>
  Opinion o reporte
  <textarea name="message" rows="5" placeholder="Cuentanos que ruta falta, que problema viste o que funcion necesitas." required>{{ old('message', $opinion->message ?? '') }}</textarea>
  @error('message') <span class="error-text">{{ $message }}</span> @enderror
</label>

@if (isset($opinion) && $opinion->exists)
<label>
  Estado
  <select name="status" required>
    @foreach (['nuevo' => 'Nuevo', 'revisado' => 'Revisado', 'archivado' => 'Archivado'] as $value => $label)
      <option value="{{ $value }}" @selected(old('status', $opinion->status) === $value)>{{ $label }}</option>
    @endforeach
  </select>
  @error('status') <span class="error-text">{{ $message }}</span> @enderror
</label>
@else
<input type="hidden" name="status" value="nuevo">
@endif
