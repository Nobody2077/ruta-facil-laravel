@extends('layouts.app')

@section('title', 'Editar opinion | Ruta Facil')

@section('content')
  <main class="page-main">
    <section class="page-hero">
      <p class="eyebrow">Editar</p>
      <h1>Actualizar opinion de {{ $opinion->name }}.</h1>
      <p>Modifica el reporte, la ruta o el estado de revision.</p>
    </section>

    <section class="section">
      <form class="auth-card" method="POST" action="{{ route('opinions.update', $opinion) }}">
        @method('PUT')
        @include('opinions._form')
        <button class="button button-primary" type="submit">Actualizar opinion</button>
        <a class="button" href="{{ route('opinions.show', $opinion) }}">Cancelar</a>
      </form>
    </section>
  </main>
@endsection
