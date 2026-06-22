@extends('layouts.app')

@section('title', 'Iniciar sesion | Ruta Facil')

@section('content')
<main class="flex min-h-screen items-center justify-center bg-paper px-4 pb-12 pt-28 sm:pt-32">
  <div class="grid w-full max-w-4xl overflow-hidden rounded-2xl border border-line bg-surface shadow-rf md:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">

    {{-- ANDEN: la linea del teleferico con estaciones reales de la app --}}
    <aside class="relative flex flex-col gap-8 bg-ink px-7 py-9 text-white sm:px-9" aria-hidden="true">
      {{-- patron geometrico neo-andino, muy sutil --}}
      <div class="pointer-events-none absolute inset-0 opacity-[0.06]"
           style="background-image:linear-gradient(135deg,#fff 25%,transparent 25%),linear-gradient(225deg,#fff 25%,transparent 25%);background-size:18px 18px;"></div>

      <div class="relative flex items-center gap-2.5 font-extrabold">
        <span class="grid h-10 w-10 place-items-center rounded-lg bg-yellow text-ink">RF</span>
        <span class="text-lg">Ruta Facil</span>
      </div>

      {{-- la linea: gradiente multicolor que "asciende" al cargar --}}
      <ol class="rf-linea relative m-0 flex flex-1 list-none flex-col justify-center gap-7 p-0 pl-7">
        <span class="rf-cable" aria-hidden="true"></span>

        <li class="rf-estacion" style="--i:0">
          <span class="rf-dot" style="--c:#d6275e"></span>
          <span class="rf-rotulo">Rutas en vivo</span>
          <span class="rf-sub">Minibus, trufi y teleferico</span>
        </li>
        <li class="rf-estacion" style="--i:1">
          <span class="rf-dot" style="--c:#f4b41a"></span>
          <span class="rf-rotulo">Opiniones</span>
          <span class="rf-sub">La voz del barrio</span>
        </li>
        <li class="rf-estacion" style="--i:2">
          <span class="rf-dot" style="--c:#ef6f3c"></span>
          <span class="rf-rotulo">Recorridos</span>
          <span class="rf-sub">Paradas y horarios</span>
        </li>
        <li class="rf-estacion rf-estacion--activa" style="--i:3">
          <span class="rf-dot rf-dot--activa" style="--c:#0e9c9c"></span>
          <span class="rf-rotulo">Tu cuenta</span>
          <span class="rf-sub">Estas aqui</span>
        </li>
      </ol>

      <p class="relative m-0 text-xs font-bold uppercase tracking-[0.18em] text-white/55">
        El Alto, Bolivia &middot; 4150 m
      </p>
    </aside>

    {{-- DESTINO: el formulario --}}
    <section class="flex flex-col justify-center gap-6 px-7 py-9 sm:px-10">
      <header class="grid gap-1.5">
        <p class="m-0 text-xs font-bold uppercase tracking-[0.2em] text-magenta">Acceso</p>
        <h1 class="m-0 text-3xl font-extrabold leading-tight text-ink sm:text-4xl">Bienvenido de vuelta</h1>
        <p class="m-0 text-muted">Entra a tu cuenta para opinar y seguir tus rutas en Ruta Facil.</p>
      </header>

      @if ($errors->any())
        <div role="alert" class="flex items-start gap-2.5 rounded-lg border border-red/30 bg-red/10 px-4 py-3 text-sm font-semibold text-red">
          <span aria-hidden="true" class="mt-0.5">&#9888;</span>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      @if (session('status'))
        <div role="status" class="rounded-lg border border-green/30 bg-green/10 px-4 py-3 text-sm font-semibold text-green-dark">
          {{ session('status') }}
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}" class="grid gap-4">
        @csrf

        <div class="grid gap-1.5">
          <label for="email" class="text-xs font-bold uppercase tracking-[0.14em] text-ink">Correo electronico</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                 autofocus placeholder="tucorreo@ejemplo.com"
                 class="w-full rounded-lg border border-line bg-white px-4 py-3 text-ink outline-none transition focus-visible:border-green focus-visible:ring-2 focus-visible:ring-green/35">
        </div>

        <div class="grid gap-1.5">
          <label for="password" class="text-xs font-bold uppercase tracking-[0.14em] text-ink">Contrasena</label>
          <input id="password" type="password" name="password" required autocomplete="current-password"
                 placeholder="Tu contrasena"
                 class="w-full rounded-lg border border-line bg-white px-4 py-3 text-ink outline-none transition focus-visible:border-green focus-visible:ring-2 focus-visible:ring-green/35">
        </div>

        <button type="submit"
                class="group mt-1 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green px-4 py-3 font-bold text-white shadow-[0_12px_24px_rgba(14,143,104,0.26)] outline-none transition hover:-translate-y-0.5 hover:bg-green-dark focus-visible:ring-2 focus-visible:ring-green/45 active:translate-y-0">
          Iniciar sesion
          <span aria-hidden="true" class="transition-transform group-hover:translate-x-1">&rarr;</span>
        </button>
      </form>

      <p class="m-0 text-center text-sm text-muted">
        No tienes cuenta?
        <a href="{{ route('register') }}" class="font-extrabold text-green underline-offset-2 hover:underline">Registrate aqui</a>
      </p>
    </section>
  </div>
</main>

@include('auth.partials.estilo-red')
@endsection
