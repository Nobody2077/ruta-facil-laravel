@extends('layouts.app')

@section('title', 'Crear opinion | Ruta Facil')

@section('content')
  <main class="page-main">
    <section class="page-hero">
      <p class="eyebrow">Crear</p>
      <h1>Registrar una opinion o reporte de ruta.</h1>
      <p>Completa el formulario para registrar tu reporte o sugerencia sobre una ruta.</p>
    </section>

    <section class="section">
      <form class="auth-card" method="POST" action="{{ route('opinions.store') }}">
        @include('opinions._form')
        <button class="button button-primary" type="submit">Guardar opinion</button>
        <a class="button" href="{{ route('opinions.index') }}">Volver al listado</a>
      </form>
    </section>
  </main>
@endsection
