@extends('layouts.app')

@section('title', 'Opiniones | Ruta Facil')

@php
    $statusColors = ['nuevo' => 'teal', 'revisado' => 'green', 'archivado' => 'neutral'];
@endphp

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(1160px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-magenta">CRUD Laravel</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Opiniones de usuarios sobre rutas de El Alto.</h1>
      <p class="mb-5 text-muted">Lista, crea, revisa, edita y elimina comentarios guardados en MySQL.</p>
      <x-button variant="primary" href="{{ route('opinions.create') }}">Crear opinion</x-button>
    </section>

    <section class="mx-auto w-[min(1160px,calc(100%-2rem))] pb-12">
      @if (session('status'))
        <x-alert type="success" class="mb-6">{{ session('status') }}</x-alert>
      @endif

      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($opinions as $opinion)
          <x-card class="flex flex-col gap-2">
            <div class="flex flex-wrap items-center gap-2">
              <x-badge color="{{ $statusColors[$opinion->status] ?? 'neutral' }}">{{ ucfirst($opinion->status) }}</x-badge>
              @if ($opinion->project?->category)
                <x-badge color="neutral">{{ $opinion->project->category->name }}</x-badge>
              @endif
            </div>
            <h3 class="text-lg font-bold">{{ $opinion->name }}</h3>
            <p class="text-sm font-bold text-teal">
              {{ $opinion->project?->title ?? $opinion->route }} - {{ $opinion->created_at->format('d/m/Y') }}
            </p>
            <p class="text-sm text-muted">{{ \Illuminate\Support\Str::limit($opinion->message, 140) }}</p>
            <div class="mt-2 flex flex-wrap items-center gap-2.5">
              <x-button href="{{ route('opinions.show', $opinion) }}">Ver</x-button>
              @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderador'))
                  <x-button href="{{ route('opinions.edit', $opinion) }}">Editar</x-button>
                @endif
                @if (auth()->user()->hasRole('admin'))
                  <form method="POST" action="{{ route('opinions.destroy', $opinion) }}" class="m-0" onsubmit="return confirm('¿Eliminar esta opinion? Esta accion no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <x-button variant="danger" type="submit">Eliminar</x-button>
                  </form>
                @endif
              @endauth
            </div>
          </x-card>
        @empty
          <p class="rounded-lg border border-dashed border-line p-4 text-muted">Aun no existen opiniones. Registra la primera opinion de prueba.</p>
        @endforelse
      </div>

      <div class="mt-6">
        {{ $opinions->links() }}
      </div>
    </section>
  </main>
@endsection
