<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Ruta Facil')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  {{-- CSS heredado: estilos de las vistas aun no migradas a Tailwind (opiniones, auth, reportes) --}}
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>
<body class="font-sans antialiased">
  <header class="fixed inset-x-0 top-0 z-30 flex flex-col gap-3 bg-[rgba(11,19,24,0.92)] px-4 py-4 text-white backdrop-blur-md sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-12">
    <a class="flex items-center gap-2.5 font-extrabold no-underline text-white" href="{{ route('home') }}" aria-label="Ruta Facil inicio">
      <span class="grid h-10 w-10 place-items-center rounded-lg bg-yellow text-ink">RF</span>
      <span>Ruta Facil</span>
    </a>
    <nav class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-bold sm:text-base" aria-label="Navegacion principal">
      <a class="no-underline hover:text-yellow transition" href="{{ route('home') }}#rutas">Rutas</a>
      <a class="no-underline hover:text-yellow transition" href="{{ route('home') }}#comunidad">Comunidad</a>
      <a class="no-underline hover:text-yellow transition" href="{{ route('opinions.index') }}">Opiniones</a>
      <a class="no-underline hover:text-yellow transition" href="{{ route('recorridos.index') }}">Recorridos</a>
      <a class="no-underline hover:text-yellow transition" href="{{ route('ayuda') }}">Ayuda</a>

      @guest
        <a class="no-underline hover:text-yellow transition" href="{{ route('login') }}">Ingresar</a>
        <a class="rounded-full border border-white/65 px-3 py-2 no-underline hover:bg-white/10 transition" href="{{ route('register') }}">Registrarse</a>
      @else
        @php
          $navRole = auth()->user()->roles->first();
          $unreadCount = auth()->user()->unreadNotifications()->count();
        @endphp
        <a href="{{ route('notificaciones.index') }}" class="relative text-lg no-underline transition hover:text-yellow" aria-label="Notificaciones" title="Notificaciones">
          &#128276;
          @if ($unreadCount > 0)
            <span class="absolute -right-2 -top-2 grid h-5 min-w-[1.25rem] place-items-center rounded-full bg-red px-1 text-[0.65rem] font-black text-white">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
          @endif
        </a>
        <span class="flex items-center gap-2 text-sm text-white/90">
          {{ auth()->user()->name }}
          @if ($navRole)
            <x-badge :role="$navRole->slug">{{ $navRole->slug }}</x-badge>
          @endif
        </span>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
          @csrf
          <button type="submit" class="rounded-full border border-white/65 bg-transparent px-3 py-2 font-bold text-white hover:bg-white/10 transition cursor-pointer">Salir</button>
        </form>
      @endguest
    </nav>
  </header>

  @yield('content')

  <footer class="flex flex-col items-start justify-between gap-3 bg-[#0f171c] px-4 py-6 text-white sm:flex-row sm:items-center sm:px-6 lg:px-12">
    <p class="m-0 text-white/70">Ruta Facil &mdash; Movilidad urbana inteligente para El Alto, Bolivia.</p>
    <a class="font-bold no-underline text-white hover:text-yellow transition" href="{{ route('ayuda') }}">Ayuda</a>
    @auth
      <a class="font-bold no-underline text-white hover:text-yellow transition" href="{{ route('opinions.create') }}">Dejar una opinion</a>
    @else
      <a class="font-bold no-underline text-white hover:text-yellow transition" href="{{ route('login') }}">Iniciar sesion</a>
    @endauth
  </footer>

  @stack('scripts')
</body>
</html>
