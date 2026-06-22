@extends('layouts.app')

@section('title', 'Recorridos | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(1160px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-magenta">Cabecera - detalle</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Recorridos de transporte y sus paradas.</h1>
      <p class="mb-5 text-muted">Cada recorrido (cabecera) agrupa varias paradas (detalle). Lista, crea, revisa, edita y elimina.</p>
      @auth
        @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderador'))
          <x-button variant="primary" href="{{ route('recorridos.create') }}">Crear recorrido</x-button>
        @endif
      @endauth
    </section>

    <section class="mx-auto w-[min(1160px,calc(100%-2rem))] pb-12">
      @if (session('status'))
        <x-alert type="success" class="mb-6">{{ session('status') }}</x-alert>
      @endif

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($recorridos as $recorrido)
          <x-card class="flex flex-col gap-2">
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
            <h3 class="text-lg font-bold">{{ $recorrido->nombre }}</h3>
            <p class="text-sm font-bold text-teal">{{ $recorrido->origen }} → {{ $recorrido->destino }}</p>
            <p class="text-sm text-muted">{{ $recorrido->paradas_count }} parada(s) registradas.</p>
            <div class="mt-2 flex flex-wrap items-center gap-2.5">
              <x-button href="{{ route('recorridos.show', $recorrido) }}">Ver</x-button>
              @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderador'))
                  <x-button href="{{ route('recorridos.edit', $recorrido) }}">Editar</x-button>
                @endif
                @if (auth()->user()->hasRole('admin'))
                  <form method="POST" action="{{ route('recorridos.destroy', $recorrido) }}" class="m-0" onsubmit="return confirm('¿Eliminar este recorrido y todas sus paradas? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <x-button variant="danger" type="submit">Eliminar</x-button>
                  </form>
                @endif
              @endauth
            </div>
          </x-card>
        @empty
          <p class="rounded-lg border border-dashed border-line p-4 text-muted">Aún no existen recorridos. Crea el primero para probar el CRUD cabecera-detalle.</p>
        @endforelse
      </div>

      <div class="mt-6">
        {{ $recorridos->links() }}
      </div>
    </section>
  </main>
@endsection
