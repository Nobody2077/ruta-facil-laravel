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
      <a class="nav-action" href="{{ route('opinions.create') }}">Opinar</a>
    </nav>
  </header>

  @yield('content')

  <footer class="site-footer">
    <p>Ruta Facil - Proyecto de movilidad urbana inteligente para El Alto, Bolivia.</p>
    <a href="{{ route('opinions.create') }}">Registrarme como usuario piloto</a>
  </footer>
</body>
</html>
