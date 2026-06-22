@extends('layouts.app')

@section('title', 'Editar opinion | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(720px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-magenta">Editar</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Actualizar opinion de {{ $opinion->name }}.</h1>
      <p class="mb-5 text-muted">Modifica el reporte, la ruta o el estado de revision.</p>
    </section>

    <section class="mx-auto w-[min(720px,calc(100%-2rem))] pb-12">
      <x-card>
        <form method="POST" action="{{ route('opinions.update', $opinion) }}" class="grid gap-5">
          @method('PUT')
          @include('opinions._form')
          <div class="flex flex-wrap items-center gap-2.5">
            <x-button variant="primary" type="submit">Actualizar opinion</x-button>
            <x-button href="{{ route('opinions.show', $opinion) }}">Cancelar</x-button>
          </div>
        </form>
      </x-card>
    </section>
  </main>
@endsection
