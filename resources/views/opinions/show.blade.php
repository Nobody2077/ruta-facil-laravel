@extends('layouts.app')

@section('title', 'Detalle de opinion | Ruta Facil')

@php
    $statusColors = ['nuevo' => 'teal', 'revisado' => 'green', 'archivado' => 'neutral'];
@endphp

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(820px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-magenta">Detalle</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">{{ $opinion->name }}</h1>
      <p class="mb-0 text-muted">{{ $opinion->route }} - Estado: {{ ucfirst($opinion->status) }}</p>
    </section>

    <section class="mx-auto w-[min(820px,calc(100%-2rem))] pb-12">
      @if (session('status'))
        <x-alert type="success" class="mb-6">{{ session('status') }}</x-alert>
      @endif

      <x-card class="flex flex-col gap-4">
        <div class="flex flex-wrap items-center gap-2">
          <x-badge color="{{ $statusColors[$opinion->status] ?? 'neutral' }}">{{ ucfirst($opinion->status) }}</x-badge>
          <x-badge color="neutral">{{ $opinion->created_at->format('d/m/Y H:i') }}</x-badge>
        </div>

        @if ($opinion->project)
          <p class="m-0 text-sm">
            <strong>Proyecto:</strong> {{ $opinion->project->title }}
            @if ($opinion->project->category)
              <br><strong>Categoria:</strong> {{ $opinion->project->category->name }}
            @endif
          </p>
        @endif

        @if ($opinion->user)
          <p class="m-0 text-sm"><strong>Usuario vinculado:</strong> {{ $opinion->user->name }}</p>
        @endif

        <p class="m-0 leading-relaxed">{{ $opinion->message }}</p>

        @if ($opinion->comments->isNotEmpty())
          <div class="border-t border-line pt-4">
            <h3 class="mb-2 text-lg font-bold">Comentarios relacionados</h3>
            <div class="grid gap-2">
              @foreach ($opinion->comments as $comment)
                <p class="m-0 text-sm">
                  <strong>{{ $comment->user?->name ?? 'Usuario anonimo' }}:</strong>
                  {{ $comment->body }}
                </p>
              @endforeach
            </div>
          </div>
        @endif

        <div class="mt-2 flex flex-wrap items-center gap-2.5">
          <x-button href="{{ route('opinions.index') }}">Listado</x-button>
          @auth
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderador'))
              <x-button variant="primary" href="{{ route('opinions.edit', $opinion) }}">Editar</x-button>
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
    </section>
  </main>
@endsection
