@extends('layouts.app')

@section('title', 'Iniciar sesion | Ruta Facil')

@section('content')
<main class="page-main">
  <div class="section" style="max-width: 480px;">
    <div class="auth-card">
      <div>
        <p class="eyebrow">Acceso</p>
        <h2 style="font-size: 2rem; margin-bottom: 0.25rem;">Iniciar sesion</h2>
        <p>Accede con tu cuenta para participar en Ruta Facil.</p>
      </div>

      @if ($errors->any())
        <div class="success-alert" style="background: #fdecea; color: var(--red);">
          {{ $errors->first() }}
        </div>
      @endif

      @if (session('status'))
        <div class="success-alert">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <label>
          Correo electronico
          <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        </label>
        <label>
          Contrasena
          <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit" class="button button-primary" style="width: 100%; margin-top: 0.5rem;">Ingresar</button>
      </form>

      <p style="text-align: center; margin: 0;">
        No tienes cuenta?
        <a href="{{ route('register') }}" style="color: var(--green); font-weight: 800;">Registrate aqui</a>
      </p>
    </div>
  </div>
</main>
@endsection
