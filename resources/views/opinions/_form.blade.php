@csrf

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

<label>
  Estado
  <select name="status" required>
    @foreach (['nuevo' => 'Nuevo', 'revisado' => 'Revisado', 'archivado' => 'Archivado'] as $value => $label)
      <option value="{{ $value }}" @selected(old('status', $opinion->status ?? 'nuevo') === $value)>{{ $label }}</option>
    @endforeach
  </select>
  @error('status') <span class="error-text">{{ $message }}</span> @enderror
</label>
