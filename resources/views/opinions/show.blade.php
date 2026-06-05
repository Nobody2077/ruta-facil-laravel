@extends('layouts.app')

@section('title', 'Detalle de opinion | Ruta Facil')

@section('content')
  <main class="page-main">
    <section class="page-hero">
      <p class="eyebrow">Detalle</p>
      <h1>{{ $opinion->name }}</h1>
      <p>{{ $opinion->route }} - Estado: {{ ucfirst($opinion->status) }}</p>
    </section>

    <section class="section">
      @if (session('status'))
        <p class="success-alert">{{ session('status') }}</p>
      @endif

      <article class="auth-card">
        <span class="tag">{{ $opinion->created_at->format('d/m/Y H:i') }}</span>
        @if ($opinion->project)
          <p>
            <strong>Proyecto:</strong> {{ $opinion->project->title }}
            @if ($opinion->project->category)
              <br><strong>Categoria:</strong> {{ $opinion->project->category->name }}
            @endif
          </p>
        @endif
        @if ($opinion->user)
          <p><strong>Usuario vinculado:</strong> {{ $opinion->user->name }}</p>
        @endif
        <p>{{ $opinion->message }}</p>
        @if ($opinion->comments->isNotEmpty())
          <h3>Comentarios relacionados</h3>
          @foreach ($opinion->comments as $comment)
            <p>
              <strong>{{ $comment->user?->name ?? 'Usuario anonimo' }}:</strong>
              {{ $comment->body }}
            </p>
          @endforeach
        @endif
        <div class="card-actions">
          <a class="button" href="{{ route('opinions.index') }}">Listado</a>
          @auth
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderador'))
              <a class="button button-primary" href="{{ route('opinions.edit', $opinion) }}">Editar</a>
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
    </section>
  </main>
@endsection
