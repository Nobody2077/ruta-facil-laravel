@extends('layouts.app')

@section('title', 'Detalle de recorrido | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(960px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-magenta">Detalle</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">{{ $recorrido->nombre }}</h1>
      <p class="text-sm font-bold text-teal">{{ $recorrido->origen }} → {{ $recorrido->destino }}</p>
    </section>

    <section class="mx-auto grid w-[min(960px,calc(100%-2rem))] gap-6 pb-12">
      @if (session('status'))
        <x-alert type="success">{{ session('status') }}</x-alert>
      @endif

      {{-- Cabecera --}}
      <x-card class="grid gap-3">
        <div class="flex flex-wrap items-center gap-2">
          <x-badge color="teal">{{ $recorrido->codigo }}</x-badge>
          @if ($recorrido->activo)
            <x-badge color="green">Activo</x-badge>
          @else
            <x-badge color="red">Inactivo</x-badge>
          @endif
          @if ($recorrido->category)
            <x-badge color="neutral">{{ $recorrido->category->name }}</x-badge>
          @endif
        </div>
        @if ($recorrido->tarifa_bs)
          <p><strong>Tarifa:</strong> {{ $recorrido->tarifa_bs }} Bs</p>
        @endif
        @if ($recorrido->descripcion)
          <p class="text-muted">{{ $recorrido->descripcion }}</p>
        @endif
        @if ($recorrido->creator)
          <p class="text-sm text-muted"><strong>Registrado por:</strong> {{ $recorrido->creator->name }}</p>
        @endif
      </x-card>

      {{-- Detalle: paradas --}}
      <x-card class="grid gap-4">
        <h3 class="text-lg font-extrabold">Paradas del recorrido ({{ $recorrido->paradas->count() }})</h3>
        @if ($recorrido->paradas->isEmpty())
          <p class="text-muted">Este recorrido no tiene paradas registradas.</p>
        @else
          <ol class="grid gap-3">
            @foreach ($recorrido->paradas as $parada)
              <li class="flex items-start gap-3 rounded-lg border border-line bg-paper p-3">
                <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-green font-black text-white">{{ $parada->orden }}</span>
                <div>
                  <p class="font-bold">{{ $parada->nombre }}</p>
                  @if ($parada->referencia)
                    <p class="text-sm text-muted">{{ $parada->referencia }}</p>
                  @endif
                  @if ($parada->latitud && $parada->longitud)
                    <p class="text-xs text-muted">{{ $parada->latitud }}, {{ $parada->longitud }}</p>
                  @endif
                </div>
              </li>
            @endforeach
          </ol>
        @endif
      </x-card>

      <div class="flex flex-wrap items-center gap-2.5">
        <x-button href="{{ route('recorridos.index') }}">Listado</x-button>
        @auth
          @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderador'))
            <x-button variant="primary" href="{{ route('recorridos.edit', $recorrido) }}">Editar</x-button>
          @endif
          @if (auth()->user()->hasRole('admin'))
            <form method="POST" action="{{ route('recorridos.destroy', $recorrido) }}" class="m-0" onsubmit="return confirm('¿Eliminar este recorrido y todas sus paradas?')">
              @csrf
              @method('DELETE')
              <x-button variant="danger" type="submit">Eliminar</x-button>
            </form>
          @endif
        @endauth
      </div>
    </section>
  </main>
@endsection
