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
        <p>{{ $opinion->message }}</p>
        <div class="card-actions">
          <a class="button" href="{{ route('opinions.index') }}">Listado</a>
          <a class="button button-primary" href="{{ route('opinions.edit', $opinion) }}">Editar</a>
          <form method="POST" action="{{ route('opinions.destroy', $opinion) }}">
            @csrf
            @method('DELETE')
            <button class="button button-danger" type="submit">Eliminar</button>
          </form>
        </div>
      </article>
    </section>
  </main>
@endsection
