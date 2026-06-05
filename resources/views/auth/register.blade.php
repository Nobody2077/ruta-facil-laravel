@extends('layouts.app')

@section('title', 'Registrarse | Ruta Facil')

@section('content')
<main class="page-main">
  <div class="section" style="max-width: 480px;">
    <div class="auth-card">
      <div>
        <p class="eyebrow">Nueva cuenta</p>
        <h2 style="font-size: 2rem; margin-bottom: 0.25rem;">Registrarse</h2>
        <p>Crea tu cuenta para dejar opiniones y participar en Ruta Facil.</p>
      </div>

      @if ($errors->any())
        <div class="success-alert" style="background: #fdecea; color: var(--red);">
          @foreach ($errors->all() as $error)
            <p style="margin: 0;">{{ $error }}</p>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf
        <label>
          Nombre completo
          <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name">
        </label>
        <label>
          Correo electronico
          <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
        </label>
        <label>
          Contrasena
          <span style="font-weight: 400; color: var(--muted); font-size: 0.88rem;">(minimo 8 caracteres)</span>
          <input type="password" name="password" required autocomplete="new-password">
        </label>
        <label>
          Confirmar contrasena
          <input type="password" name="password_confirmation" required autocomplete="new-password">
        </label>
        <button type="submit" class="button button-primary" style="width: 100%; margin-top: 0.5rem;">Crear cuenta</button>
      </form>

      <p style="text-align: center; margin: 0;">
        Ya tienes cuenta?
        <a href="{{ route('login') }}" style="color: var(--green); font-weight: 800;">Inicia sesion</a>
      </p>
    </div>
  </div>
</main>
@endsection
