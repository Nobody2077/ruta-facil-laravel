@extends('layouts.app')

@section('title', 'Notificaciones | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(960px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-magenta">Alarmas del sistema</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Centro de notificaciones</h1>
      <p class="text-muted">Avisos internos del sistema, como nuevas opiniones ciudadanas registradas.</p>
    </section>

    <section class="mx-auto grid w-[min(960px,calc(100%-2rem))] gap-4 pb-12">
      @if (session('status'))
        <x-alert type="success">{{ session('status') }}</x-alert>
      @endif

      @if (auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('notificaciones.read-all') }}" class="m-0">
          @csrf
          <x-button type="submit">Marcar todas como leídas</x-button>
        </form>
      @endif

      @forelse ($notifications as $notification)
        @php
          $data = $notification->data;
          $isUnread = $notification->read_at === null;
        @endphp
        <x-card class="flex flex-col gap-2 {{ $isUnread ? 'border-l-4 border-l-green' : 'opacity-75' }}">
          <div class="flex flex-wrap items-center gap-2">
            @if ($isUnread)
              <x-badge color="green">Nueva</x-badge>
            @else
              <x-badge color="neutral">Leída</x-badge>
            @endif
            <span class="font-bold">{{ $data['titulo'] ?? 'Notificación' }}</span>
            <span class="text-xs text-muted">{{ $notification->created_at->diffForHumans() }}</span>
          </div>

          @if (($data['tipo'] ?? null) === 'nueva_opinion')
            <p class="text-sm">
              <strong>{{ $data['nombre'] ?? 'Anónimo' }}</strong>
              @if (!empty($data['route'])) · <span class="text-teal">{{ $data['route'] }}</span> @endif
            </p>
            <p class="text-sm text-muted">{{ $data['extracto'] ?? '' }}</p>
          @endif

          <div class="mt-1 flex flex-wrap items-center gap-2.5">
            @if (!empty($data['url']))
              <x-button href="{{ $data['url'] }}">Ver detalle</x-button>
            @endif
            @if ($isUnread)
              <form method="POST" action="{{ route('notificaciones.read', $notification->id) }}" class="m-0">
                @csrf
                <x-button type="submit">Marcar como leída</x-button>
              </form>
            @endif
          </div>
        </x-card>
      @empty
        <p class="rounded-lg border border-dashed border-line p-6 text-center text-muted">No tienes notificaciones por ahora.</p>
      @endforelse

      <div class="mt-2">
        {{ $notifications->links() }}
      </div>
    </section>
  </main>
@endsection
