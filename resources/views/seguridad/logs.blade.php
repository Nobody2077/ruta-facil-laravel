@extends('layouts.app')

@section('title', 'Logs de seguridad | Ruta Facil')

@php
  $eventStyles = [
    'login'         => ['green',   'Inicio de sesión'],
    'logout'        => ['neutral', 'Cierre de sesión'],
    'login_failed'  => ['red',     'Login fallido'],
    'access_denied' => ['yellow',  'Acceso denegado'],
  ];
@endphp

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(1160px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-green">Panel de administración</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Logs de seguridad</h1>
      <p class="mb-5 text-muted">Auditoría de inicios de sesión, intentos fallidos, cierres de sesión y accesos denegados por rol.</p>

      <form method="GET" action="{{ route('seguridad.logs') }}" class="flex flex-wrap items-end gap-3">
        <label class="grid gap-1 text-xs font-extrabold uppercase text-muted">
          Filtrar por evento
          <select name="event" class="w-56 rounded-md border border-line bg-white px-3.5 py-2.5 text-ink focus:border-green focus:outline-none focus:ring-2 focus:ring-green/30">
            <option value="">Todos los eventos</option>
            @foreach ($events as $ev)
              <option value="{{ $ev }}" @selected($event === $ev)>{{ $eventStyles[$ev][1] ?? $ev }}</option>
            @endforeach
          </select>
        </label>
        <x-button variant="primary" type="submit">Filtrar</x-button>
        @if ($event)
          <x-button href="{{ route('seguridad.logs') }}">Limpiar</x-button>
        @endif
      </form>
    </section>

    <section class="mx-auto w-[min(1160px,calc(100%-2rem))] pb-12">
      <x-card padding="p-0" class="overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full border-collapse text-sm">
            <thead>
              <tr class="bg-[#eef5f0] text-left">
                <th class="p-3">Evento</th>
                <th class="p-3">Usuario / Email</th>
                <th class="p-3">Descripción</th>
                <th class="p-3">IP</th>
                <th class="p-3 whitespace-nowrap">Fecha</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($logs as $log)
                @php [$color, $label] = $eventStyles[$log->event] ?? ['neutral', $log->event]; @endphp
                <tr class="border-b border-line/60 align-top">
                  <td class="p-3"><x-badge :color="$color">{{ $label }}</x-badge></td>
                  <td class="p-3">
                    <span class="font-bold">{{ $log->user?->name ?? '—' }}</span>
                    @if ($log->email)<br><span class="text-xs text-muted">{{ $log->email }}</span>@endif
                  </td>
                  <td class="p-3 text-muted">{{ $log->description }}</td>
                  <td class="p-3 whitespace-nowrap text-muted">{{ $log->ip_address ?? '—' }}</td>
                  <td class="p-3 whitespace-nowrap text-muted">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="p-6 text-center text-muted">No hay registros de seguridad{{ $event ? ' para este filtro' : '' }}.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </x-card>

      <div class="mt-6">
        {{ $logs->links() }}
      </div>
    </section>
  </main>
@endsection
