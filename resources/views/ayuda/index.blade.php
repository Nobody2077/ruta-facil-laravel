@extends('layouts.app')

@section('title', 'Ayuda | Ruta Facil')

@section('content')
  <main class="pt-28 text-ink">
    <section class="mx-auto w-[min(960px,calc(100%-2rem))] py-8">
      <p class="mb-2 text-xs font-extrabold uppercase text-green">Centro de ayuda</p>
      <h1 class="mb-2 text-3xl font-extrabold sm:text-4xl">Preguntas frecuentes</h1>
      <p class="text-muted">Encuentra cómo usar Ruta Facil según tu rol. ¿No ves tu duda? Deja una opinión y te responderemos.</p>
    </section>

    <section class="mx-auto grid w-[min(960px,calc(100%-2rem))] gap-8 pb-12">

      {{-- General --}}
      <div class="grid gap-3">
        <h2 class="text-xl font-extrabold text-green-dark">General</h2>
        <x-faq question="¿Qué es Ruta Facil?">
          Ruta Facil es una plataforma para consultar y mejorar la información del transporte público de
          El Alto, Bolivia: recorridos, paradas, tiempos y opiniones ciudadanas.
        </x-faq>
        <x-faq question="¿Necesito una cuenta para usarla?">
          No para consultar: las opiniones y los recorridos son de lectura pública. Para participar
          (dejar opiniones o recibir notificaciones) debes <a class="font-bold text-green" href="{{ route('register') }}">registrarte</a>.
        </x-faq>
        <x-faq question="¿Cómo creo una cuenta?">
          Entra a <a class="font-bold text-green" href="{{ route('register') }}">Registrarse</a>, completa tu nombre,
          correo y contraseña. Tu cuenta se crea con el rol <strong>usuario</strong> por defecto.
        </x-faq>
      </div>

      {{-- Usuarios --}}
      <div class="grid gap-3">
        <h2 class="text-xl font-extrabold text-green-dark">Para usuarios</h2>
        <x-faq question="¿Cómo dejo una opinión sobre una ruta?">
          Inicia sesión y ve a <a class="font-bold text-green" href="{{ route('opinions.create') }}">Crear opinión</a>.
          Indica la zona o ruta, tu nombre y tu reporte. Quedará registrada para que la comunidad y los moderadores la revisen.
        </x-faq>
        <x-faq question="¿Dónde veo los recorridos y sus paradas?">
          En la sección <a class="font-bold text-green" href="{{ route('recorridos.index') }}">Recorridos</a>.
          Cada recorrido muestra su origen, destino y la lista ordenada de paradas.
        </x-faq>
        <x-faq question="¿Qué son las notificaciones (campana 🔔)?">
          Son alarmas internas del sistema. Por ejemplo, los moderadores y administradores reciben un aviso
          cuando entra una nueva opinión ciudadana. Las ves en el ícono de campana del menú superior.
        </x-faq>
      </div>

      {{-- Moderadores --}}
      <div class="grid gap-3">
        <h2 class="text-xl font-extrabold text-green-dark">Para moderadores</h2>
        <x-faq question="¿Qué puedo hacer como moderador?">
          Puedes revisar y editar opiniones ciudadanas, crear y editar recorridos con sus paradas,
          y consultar los reportes del sistema. No puedes eliminar registros (eso es exclusivo del administrador).
        </x-faq>
        <x-faq question="¿Cómo consulto los reportes?">
          Desde tu panel en el inicio o en <a class="font-bold text-green" href="{{ route('reportes.resumen') }}">Reporte del sistema</a>.
          Incluye el resumen general y el reporte de recorridos, ambos exportables a PDF.
        </x-faq>
      </div>

      {{-- Administradores --}}
      <div class="grid gap-3">
        <h2 class="text-xl font-extrabold text-green-dark">Para administradores</h2>
        <x-faq question="¿Qué control adicional tengo como administrador?">
          Acceso total: crear, editar y <strong>eliminar</strong> opiniones y recorridos, ver todos los reportes
          y acceder a los <a class="font-bold text-green" href="{{ route('seguridad.logs') }}">logs de seguridad</a>.
        </x-faq>
        <x-faq question="¿Qué registran los logs de seguridad?">
          Inicios de sesión, intentos fallidos, cierres de sesión y accesos denegados por falta de permisos,
          con la fecha, el usuario y la dirección IP. Se pueden filtrar por tipo de evento.
        </x-faq>
        <x-faq question="¿Cómo descargo un reporte en PDF?">
          En cualquier reporte (resumen o recorridos) usa el botón <strong>Descargar PDF</strong>.
          El documento se genera automáticamente con los datos actuales.
        </x-faq>
      </div>

      {{-- API --}}
      <div class="grid gap-3">
        <h2 class="text-xl font-extrabold text-green-dark">Sobre la API</h2>
        <x-faq question="¿Ruta Facil tiene una API?">
          Sí. Expone una API REST con autenticación por token (Laravel Sanctum) para opiniones, proyectos,
          categorías, recorridos, reportes y notificaciones. Los permisos siguen los mismos roles que la web.
        </x-faq>
        <x-faq question="¿Cómo me autentico en la API?">
          Haz <code class="rounded bg-paper px-1">POST /api/auth/login</code> con tu correo y contraseña para obtener un token,
          y envíalo en la cabecera <code class="rounded bg-paper px-1">Authorization: Bearer &lt;token&gt;</code>.
        </x-faq>
      </div>

      <x-card class="text-center">
        <p class="text-muted">¿Sigues con dudas?</p>
        <div class="mt-3 flex flex-wrap justify-center gap-2.5">
          <x-button variant="primary" href="{{ route('opinions.create') }}">Dejar una opinión</x-button>
          <x-button href="{{ route('home') }}">Volver al inicio</x-button>
        </div>
      </x-card>
    </section>
  </main>
@endsection
