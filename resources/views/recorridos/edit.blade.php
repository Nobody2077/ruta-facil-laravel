@extends('layouts.app')

@section('title', 'Editar recorrido | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(960px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-green">Editar</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">{{ $recorrido->nombre }}</h1>
      <p class="text-muted">Modifica la cabecera y reescribe sus paradas. El detalle se guarda en una sola transacción.</p>
    </section>

    <section class="mx-auto w-[min(960px,calc(100%-2rem))] pb-12">
      <x-card>
        <form method="POST" action="{{ route('recorridos.update', $recorrido) }}" class="grid gap-4">
          @method('PUT')
          @include('recorridos._form')
          <div class="mt-4 flex flex-wrap gap-2.5">
            <x-button variant="primary" type="submit">Actualizar recorrido</x-button>
            <x-button href="{{ route('recorridos.show', $recorrido) }}">Cancelar</x-button>
          </div>
        </form>
      </x-card>
    </section>
  </main>
@endsection
