@extends('layouts.app')

@section('title', 'Crear recorrido | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(960px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-green">Cabecera - detalle</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Registrar un recorrido y sus paradas.</h1>
      <p class="text-muted">Completa los datos del recorrido (cabecera) y agrega sus paradas (detalle) en un solo formulario.</p>
    </section>

    <section class="mx-auto w-[min(960px,calc(100%-2rem))] pb-12">
      <x-card>
        <form method="POST" action="{{ route('recorridos.store') }}" class="grid gap-4">
          @include('recorridos._form')
          <div class="mt-4 flex flex-wrap gap-2.5">
            <x-button variant="primary" type="submit">Guardar recorrido</x-button>
            <x-button href="{{ route('recorridos.index') }}">Volver al listado</x-button>
          </div>
        </form>
      </x-card>
    </section>
  </main>
@endsection
