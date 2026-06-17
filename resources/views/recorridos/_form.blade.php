@csrf

@php
    $inputClass = 'w-full rounded-md border border-line bg-white px-3.5 py-2.5 text-ink focus:border-green focus:outline-none focus:ring-2 focus:ring-green/30';
    $labelClass = 'grid gap-1.5 font-extrabold text-ink';

    $paradasData = old('paradas');
    if ($paradasData === null) {
        $paradasData = isset($recorrido)
            ? $recorrido->paradas->map(fn ($p) => [
                'orden' => $p->orden,
                'nombre' => $p->nombre,
                'referencia' => $p->referencia,
                'latitud' => $p->latitud,
                'longitud' => $p->longitud,
            ])->values()->all()
            : [];
    }
    if (empty($paradasData)) {
        $paradasData = [['orden' => 1, 'nombre' => '', 'referencia' => '', 'latitud' => '', 'longitud' => '']];
    }
@endphp

{{-- ===== CABECERA ===== --}}
<div class="grid gap-4">
    <h3 class="text-lg font-extrabold text-ink">Datos del recorrido (cabecera)</h3>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="{{ $labelClass }}">
            Nombre del recorrido
            <input type="text" name="nombre" value="{{ old('nombre', $recorrido->nombre ?? '') }}" placeholder="Ej. Linea Ceja - Rio Seco" class="{{ $inputClass }}" required>
            @error('nombre') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
        </label>

        <label class="{{ $labelClass }}">
            Código (opcional)
            <input type="text" name="codigo" value="{{ old('codigo', $recorrido->codigo ?? '') }}" placeholder="Ej. L-101 (se genera si lo dejas vacío)" class="{{ $inputClass }}">
            @error('codigo') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
        </label>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <label class="{{ $labelClass }}">
            Origen
            <input type="text" name="origen" value="{{ old('origen', $recorrido->origen ?? '') }}" placeholder="Ej. Ceja" class="{{ $inputClass }}" required>
            @error('origen') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
        </label>

        <label class="{{ $labelClass }}">
            Destino
            <input type="text" name="destino" value="{{ old('destino', $recorrido->destino ?? '') }}" placeholder="Ej. Rio Seco" class="{{ $inputClass }}" required>
            @error('destino') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
        </label>

        <label class="{{ $labelClass }}">
            Tarifa (Bs)
            <input type="number" step="0.01" min="0" name="tarifa_bs" value="{{ old('tarifa_bs', $recorrido->tarifa_bs ?? '') }}" placeholder="Ej. 2.50" class="{{ $inputClass }}">
            @error('tarifa_bs') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
        </label>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <label class="{{ $labelClass }}">
            Categoría
            <select name="category_id" class="{{ $inputClass }}">
                <option value="">Sin categoría</option>
                @foreach ($categories ?? [] as $category)
                    <option value="{{ $category->id }}" @selected((int) old('category_id', $recorrido->category_id ?? 0) === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
        </label>

        <label class="flex items-center gap-2 self-end font-extrabold text-ink">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1" class="h-5 w-5 rounded border-line text-green focus:ring-green/30" @checked(old('activo', $recorrido->activo ?? true))>
            Recorrido activo
        </label>
    </div>

    <label class="{{ $labelClass }}">
        Descripción (opcional)
        <textarea name="descripcion" rows="3" placeholder="Detalles del recorrido, frecuencia, observaciones." class="{{ $inputClass }}">{{ old('descripcion', $recorrido->descripcion ?? '') }}</textarea>
        @error('descripcion') <span class="text-sm font-bold text-red">{{ $message }}</span> @enderror
    </label>
</div>

{{-- ===== DETALLE: PARADAS ===== --}}
<div class="mt-8 grid gap-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h3 class="text-lg font-extrabold text-ink">Paradas del recorrido (detalle)</h3>
            <p class="text-sm text-muted">Agrega al menos una parada en orden. Puedes añadir o quitar filas.</p>
        </div>
        <x-button type="button" id="add-parada">+ Agregar parada</x-button>
    </div>

    @error('paradas') <x-alert type="error">{{ $message }}</x-alert> @enderror

    <div id="paradas-container" class="grid gap-3">
        @foreach ($paradasData as $i => $parada)
            <div class="parada-row rounded-lg border border-line bg-paper p-3" data-index="{{ $i }}">
                <div class="grid gap-3 md:grid-cols-[5rem_1fr_1fr]">
                    <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                        Orden
                        <input type="number" min="1" name="paradas[{{ $i }}][orden]" value="{{ $parada['orden'] ?? $i + 1 }}" class="{{ $inputClass }}">
                    </label>
                    <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                        Nombre de la parada
                        <input type="text" name="paradas[{{ $i }}][nombre]" value="{{ $parada['nombre'] ?? '' }}" placeholder="Ej. Plaza La Paz" class="{{ $inputClass }}" required>
                        @error("paradas.$i.nombre") <span class="text-sm font-bold normal-case text-red">{{ $message }}</span> @enderror
                    </label>
                    <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                        Referencia (opcional)
                        <input type="text" name="paradas[{{ $i }}][referencia]" value="{{ $parada['referencia'] ?? '' }}" placeholder="Ej. frente al mercado" class="{{ $inputClass }}">
                    </label>
                </div>
                <div class="mt-3 grid gap-3 md:grid-cols-[1fr_1fr_auto] md:items-end">
                    <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                        Latitud (opcional)
                        <input type="number" step="0.0000001" name="paradas[{{ $i }}][latitud]" value="{{ $parada['latitud'] ?? '' }}" placeholder="-16.5000000" class="{{ $inputClass }}">
                    </label>
                    <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                        Longitud (opcional)
                        <input type="number" step="0.0000001" name="paradas[{{ $i }}][longitud]" value="{{ $parada['longitud'] ?? '' }}" placeholder="-68.1500000" class="{{ $inputClass }}">
                    </label>
                    <x-button type="button" variant="danger" class="remove-parada">Quitar</x-button>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Plantilla para clonar nuevas paradas --}}
<template id="parada-template">
    <div class="parada-row rounded-lg border border-line bg-paper p-3" data-index="__INDEX__">
        <div class="grid gap-3 md:grid-cols-[5rem_1fr_1fr]">
            <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                Orden
                <input type="number" min="1" name="paradas[__INDEX__][orden]" value="__ORDEN__" class="{{ $inputClass }}">
            </label>
            <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                Nombre de la parada
                <input type="text" name="paradas[__INDEX__][nombre]" placeholder="Ej. Plaza La Paz" class="{{ $inputClass }}" required>
            </label>
            <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                Referencia (opcional)
                <input type="text" name="paradas[__INDEX__][referencia]" placeholder="Ej. frente al mercado" class="{{ $inputClass }}">
            </label>
        </div>
        <div class="mt-3 grid gap-3 md:grid-cols-[1fr_1fr_auto] md:items-end">
            <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                Latitud (opcional)
                <input type="number" step="0.0000001" name="paradas[__INDEX__][latitud]" placeholder="-16.5000000" class="{{ $inputClass }}">
            </label>
            <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
                Longitud (opcional)
                <input type="number" step="0.0000001" name="paradas[__INDEX__][longitud]" placeholder="-68.1500000" class="{{ $inputClass }}">
            </label>
            <x-button type="button" variant="danger" class="remove-parada">Quitar</x-button>
        </div>
    </div>
</template>

@push('scripts')
<script>
  (function () {
    const container = document.getElementById('paradas-container');
    const template = document.getElementById('parada-template');
    const addBtn = document.getElementById('add-parada');
    let nextIndex = {{ count($paradasData) }};

    addBtn.addEventListener('click', function () {
      const html = template.innerHTML
        .replaceAll('__INDEX__', nextIndex)
        .replaceAll('__ORDEN__', nextIndex + 1);
      const wrapper = document.createElement('div');
      wrapper.innerHTML = html.trim();
      container.appendChild(wrapper.firstChild);
      nextIndex++;
    });

    container.addEventListener('click', function (e) {
      if (e.target.classList.contains('remove-parada')) {
        const rows = container.querySelectorAll('.parada-row');
        if (rows.length <= 1) {
          alert('Debe quedar al menos una parada.');
          return;
        }
        e.target.closest('.parada-row').remove();
      }
    });
  })();
</script>
@endpush
