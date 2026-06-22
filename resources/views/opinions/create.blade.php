@extends('layouts.app')

@section('title', 'Crear opinion | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(720px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-magenta">Crear</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Registrar una opinion o reporte de ruta.</h1>
      <p class="mb-5 text-muted">Completa el formulario para registrar tu reporte o sugerencia sobre una ruta.</p>
    </section>

    <section class="mx-auto w-[min(720px,calc(100%-2rem))] pb-12">
      <x-card>
        <form method="POST" action="{{ route('opinions.store') }}" class="grid gap-5">
          @include('opinions._form')
          <div class="flex flex-wrap items-center gap-2.5">
            <x-button variant="primary" type="submit">Guardar opinion</x-button>
            <x-button href="{{ route('opinions.index') }}">Volver al listado</x-button>
          </div>
        </form>
      </x-card>
    </section>
  </main>
@endsection
