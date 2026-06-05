@extends('layouts.app')

@section('title', 'Opiniones | Ruta Facil')

@section('content')
  <main class="page-main">
    <section class="page-hero">
      <p class="eyebrow">CRUD Laravel</p>
      <h1>Opiniones de usuarios sobre rutas de El Alto.</h1>
      <p>Lista, crea, revisa, edita y elimina comentarios guardados en MySQL.</p>
      <a class="button button-primary" href="{{ route('opinions.create') }}">Crear opinion</a>
    </section>

    <section class="section">
      @if (session('status'))
        <p class="success-alert">{{ session('status') }}</p>
      @endif

      <div class="publication-grid">
        @forelse ($opinions as $opinion)
          <article class="publication-card">
            <span class="tag">{{ ucfirst($opinion->status) }}</span>
            @if ($opinion->project?->category)
              <span class="tag">{{ $opinion->project->category->name }}</span>
            @endif
            <h3>{{ $opinion->name }}</h3>
            <p class="publication-meta">
              {{ $opinion->project?->title ?? $opinion->route }} - {{ $opinion->created_at->format('d/m/Y') }}
            </p>
            <p>{{ \Illuminate\Support\Str::limit($opinion->message, 140) }}</p>
            <div class="card-actions">
              <a class="button" href="{{ route('opinions.show', $opinion) }}">Ver</a>
              @auth
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderador'))
                  <a class="button" href="{{ route('opinions.edit', $opinion) }}">Editar</a>
                @endif
                @if (auth()->user()->hasRole('admin'))
                  <form method="POST" action="{{ route('opinions.destroy', $opinion) }}">
                    @csrf
                    @method('DELETE')
                    <button class="button button-danger" type="submit" onclick="return confirm('¿Eliminar esta opinion? Esta accion no se puede deshacer.')">Eliminar</button>
                  </form>
                @endif
              @endauth
            </div>
          </article>
        @empty
          <p class="empty-state">Aun no existen opiniones. Registra la primera opinion de prueba.</p>
        @endforelse
      </div>

      <div class="pagination-wrap">
        {{ $opinions->links() }}
      </div>
    </section>
  </main>
@endsection
