@extends('layouts.app')

@section('title', 'Reporte de recorridos | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(1160px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-magenta">Panel de administración</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Reporte de recorridos y paradas</h1>
      <p class="mb-5 text-muted">Reporte cabecera-detalle: cada recorrido con sus paradas. Generado el {{ $data['generado_en'] }}.</p>
      <div class="flex flex-wrap gap-2.5">
        <x-button variant="primary" href="{{ route('reportes.recorridos.pdf') }}">Descargar PDF</x-button>
        <x-button href="{{ route('reportes.resumen') }}">Ver resumen general</x-button>
        <x-button href="{{ route('home') }}">Volver al inicio</x-button>
      </div>
    </section>

    <section class="mx-auto grid w-[min(1160px,calc(100%-2rem))] gap-6 pb-12">
      {{-- Estadísticas agregadas --}}
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
          ['Recorridos', $data['total_recorridos']],
          ['Paradas', $data['total_paradas']],
          ['Activos', $data['recorridos_activos']],
          ['Paradas/recorrido', $data['promedio_paradas']],
        ] as [$label, $value])
          <x-card class="text-center">
            <span class="block text-4xl font-black text-teal">{{ $value }}</span>
            <span class="text-sm font-bold text-muted">{{ $label }}</span>
          </x-card>
        @endforeach
      </div>

      {{-- Recorridos por categoría --}}
      <x-card class="grid gap-3">
        <h3 class="text-lg font-extrabold">Recorridos por categoría</h3>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
          @forelse ($data['recorridos_by_category'] as $item)
            <div class="rounded-lg border border-line p-3 text-center">
              <strong class="block text-2xl">{{ $item['total'] }}</strong>
              <span class="text-sm text-muted">{{ $item['category'] }}</span>
            </div>
          @empty
            <p class="text-muted">Sin datos.</p>
          @endforelse
        </div>
      </x-card>

      {{-- Detalle cabecera-detalle --}}
      <x-card class="grid gap-4">
        <h3 class="text-lg font-extrabold">Detalle por recorrido</h3>
        @forelse ($data['detalle'] as $rec)
          <div class="rounded-lg border border-line bg-paper p-4">
            <div class="flex flex-wrap items-center gap-2">
              <x-badge :color="$rec['activo'] ? 'teal' : 'red'">{{ $rec['codigo'] }}</x-badge>
              <span class="font-bold">{{ $rec['nombre'] }}</span>
            </div>
            <p class="mt-1 text-sm text-muted">
              {{ $rec['origen'] }} → {{ $rec['destino'] }} · {{ $rec['categoria'] }}
              @if ($rec['tarifa_bs']) · {{ $rec['tarifa_bs'] }} Bs @endif
              · {{ $rec['activo'] ? 'Activo' : 'Inactivo' }}
            </p>
            @if (count($rec['paradas']))
              <table class="mt-3 w-full border-collapse text-sm">
                <thead>
                  <tr class="bg-[#eef5f0] text-left">
                    <th class="w-16 p-2">Orden</th>
                    <th class="p-2">Parada</th>
                    <th class="p-2">Referencia</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($rec['paradas'] as $parada)
                    <tr class="border-b border-line/60">
                      <td class="p-2 font-bold">{{ $parada['orden'] }}</td>
                      <td class="p-2">{{ $parada['nombre'] }}</td>
                      <td class="p-2 text-muted">{{ $parada['referencia'] ?? '—' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <p class="mt-2 text-sm text-muted">Sin paradas registradas.</p>
            @endif
          </div>
        @empty
          <p class="text-muted">No hay recorridos registrados.</p>
        @endforelse
      </x-card>
    </section>
  </main>
@endsection
