@extends('layouts.app')

@section('title', 'Reporte del sistema | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(1160px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-magenta">Panel de administración</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Reporte del sistema</h1>
      <p class="mb-5 text-muted">Resumen automatizado de opiniones, proyectos y actividad ciudadana en tiempo real.</p>
      <div class="flex flex-wrap gap-2.5">
        <x-button variant="primary" href="{{ route('reportes.resumen.pdf') }}">Descargar PDF</x-button>
        <x-button href="{{ route('reportes.recorridos') }}">Reporte de recorridos</x-button>
        <x-button href="{{ route('home') }}">Volver al inicio</x-button>
      </div>
    </section>

    <section class="mx-auto grid w-[min(1160px,calc(100%-2rem))] gap-6 pb-12">
      {{-- Totales principales --}}
      <div class="grid gap-4 sm:grid-cols-2">
        @foreach ([
          ['Opiniones ciudadanas', $data['total_opinions']],
          ['Comentarios', $data['total_comments']],
        ] as [$label, $value])
          <x-card class="text-center">
            <span class="block text-5xl font-black leading-none">{{ $value }}</span>
            <span class="mt-2 block text-sm font-bold text-muted">{{ $label }}</span>
          </x-card>
        @endforeach
      </div>

      {{-- Opiniones por estado y por proyecto --}}
      <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.5fr)]">
        <x-card class="grid gap-2">
          <p class="text-xs font-extrabold uppercase text-magenta">Desglose</p>
          <h3 class="text-lg font-extrabold">Opiniones por estado</h3>
          @forelse ($data['opinions_by_status'] as $estado => $total)
            <div class="flex items-center justify-between border-b border-line py-2.5">
              <x-badge color="neutral">{{ $estado }}</x-badge>
              <strong class="text-xl">{{ $total }}</strong>
            </div>
          @empty
            <p class="text-muted">Sin datos.</p>
          @endforelse
        </x-card>

        <x-card class="grid gap-2">
          <p class="text-xs font-extrabold uppercase text-magenta">Ranking</p>
          <h3 class="text-lg font-extrabold">Opiniones por proyecto</h3>
          @forelse ($data['opinions_by_project'] as $i => $item)
            <div class="flex items-center gap-3 border-b border-line py-2.5">
              <span class="text-sm font-bold text-muted">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
              <span class="flex-1">{{ $item['project'] }}</span>
              <strong>{{ $item['total'] }}</strong>
            </div>
          @empty
            <p class="text-muted">Sin datos.</p>
          @endforelse
        </x-card>
      </div>

      {{-- Proyectos por categoria --}}
      <x-card class="grid gap-3">
        <p class="text-xs font-extrabold uppercase text-magenta">Distribución</p>
        <h3 class="text-lg font-extrabold">Proyectos por categoría</h3>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
          @forelse ($data['projects_by_category'] as $item)
            <div class="rounded-lg border border-line p-3 text-center">
              <strong class="block text-2xl">{{ $item['total'] }}</strong>
              <span class="text-sm text-muted">{{ $item['category'] }}</span>
            </div>
          @empty
            <p class="text-muted">Sin datos.</p>
          @endforelse
        </div>
      </x-card>
    </section>
  </main>
@endsection
