<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Ruta Facil')</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>
<body>
  <header class="site-header">
    <a class="brand" href="{{ route('home') }}" aria-label="Ruta Facil inicio">
      <span class="brand-mark">RF</span>
      <span>Ruta Facil</span>
    </a>
    <nav class="site-nav" aria-label="Navegacion principal">
      <a href="{{ route('home') }}#rutas">Rutas</a>
      <a href="{{ route('home') }}#comunidad">Comunidad</a>
      <a href="{{ route('opinions.index') }}">Opiniones</a>

      @guest
        <a href="{{ route('login') }}">Ingresar</a>
        <a class="nav-action" href="{{ route('register') }}">Registrarse</a>
      @else
        @php $navRole = auth()->user()->roles->first(); @endphp
        <span class="nav-user-info">
          {{ auth()->user()->name }}
          @if ($navRole)
            <span class="role-badge role-{{ $navRole->slug }}">{{ $navRole->slug }}</span>
          @endif
        </span>
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
          @csrf
          <button type="submit" class="nav-action nav-logout">Salir</button>
        </form>
      @endguest
    </nav>
  </header>

  @yield('content')

  <footer class="site-footer">
    <p>Ruta Facil &mdash; Movilidad urbana inteligente para El Alto, Bolivia.</p>
    @auth
      <a href="{{ route('opinions.create') }}">Dejar una opinion</a>
    @else
      <a href="{{ route('login') }}">Iniciar sesion</a>
    @endauth
  </footer>
</body>
</html>
