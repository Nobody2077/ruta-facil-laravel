@extends('layouts.app')

@section('title', 'Ruta Facil | Transporte publico de El Alto')

@section('content')
  <main class="text-ink">
    {{-- HERO --}}
    <section
      class="relative grid min-h-[92vh] items-end px-4 pb-20 pt-32 text-white sm:px-8 lg:px-16"
      style="background:
        linear-gradient(90deg, rgba(6,15,18,0.88) 0%, rgba(6,15,18,0.72) 35%, rgba(6,15,18,0.18) 72%),
        url('{{ asset('assets/hero-ruta-facil.png') }}') center / cover;">
      <div class="relative max-w-[980px]">
        <p class="mb-3 text-xs font-extrabold uppercase text-yellow">El Alto, Bolivia</p>
        <h1 class="mb-5 max-w-[850px] text-[clamp(2.35rem,7vw,5.9rem)] font-extrabold leading-[1.08]">
          Rutas urbanas y rurales mas claras para moverte mejor.
        </h1>
        <p class="max-w-[720px] text-[clamp(1.05rem,2vw,1.35rem)] text-white/85">
          Ruta Facil es una app movil para consultar recorridos del transporte publico,
          estimar tiempos de llegada y construir informacion confiable con ayuda de los usuarios.
        </p>
        <div class="mt-8 flex flex-wrap gap-3.5">
          <x-button variant="primary" href="#android">Quiero probar la app</x-button>
          <x-button variant="secondary" href="{{ route('opinions.create') }}">Dejar mi opinion</x-button>
        </div>
      </div>
    </section>

    {{-- INTRO BAND --}}
    <section class="bg-[#eef5f0] px-4 py-[clamp(4rem,7vw,6rem)] sm:px-8 lg:px-16" aria-label="Resumen del proyecto">
      <div class="mx-auto max-w-[1160px]">
        <div class="mb-8 max-w-[760px]">
          <p class="mb-3 text-xs font-extrabold uppercase text-green">Movilidad inteligente</p>
          <h2 class="text-[clamp(2rem,4vw,3.4rem)] font-extrabold leading-[1.08]">Una guia pensada para la realidad del transporte alteno.</h2>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
          <x-card>
            <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#dff1e9] font-black text-green-dark">01</span>
            <h3 class="mb-2 text-lg font-bold">Recorridos digitalizados</h3>
            <p class="text-muted">Mapas de minibuses, trufis y micros con rutas faciles de consultar desde el celular.</p>
          </x-card>
          <x-card>
            <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#dff1e9] font-black text-green-dark">02</span>
            <h3 class="mb-2 text-lg font-bold">Tiempos estimados</h3>
            <p class="text-muted">Calculos basados en distancia, velocidad promedio y datos compartidos por pasajeros.</p>
          </x-card>
          <x-card>
            <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#dff1e9] font-black text-green-dark">03</span>
            <h3 class="mb-2 text-lg font-bold">Reportes colaborativos</h3>
            <p class="text-muted">La comunidad puede avisar cambios, desvios y experiencias para mantener la informacion viva.</p>
          </x-card>
        </div>
      </div>
    </section>

    {{-- SPLIT: ENFOQUE LOCAL --}}
    <section id="rutas" class="mx-auto grid max-w-[1160px] scroll-mt-24 items-center gap-[clamp(2rem,5vw,4rem)] px-4 py-[clamp(4rem,7vw,6rem)] md:grid-cols-[minmax(0,1fr)_minmax(320px,0.78fr)]">
      <div>
        <p class="mb-3 text-xs font-extrabold uppercase text-green">Enfoque local</p>
        <h2 class="mb-4 text-[clamp(2rem,4vw,3.4rem)] font-extrabold leading-[1.08]">Primero El Alto: zonas, conexiones y rutas que la gente usa todos los dias.</h2>
        <p class="text-muted">
          La aplicacion nace para resolver una necesidad concreta: muchas rutas no estan
          documentadas de forma clara y los usuarios dependen de preguntar o aprender por experiencia.
        </p>
        <ul class="mt-6 grid gap-3">
          @foreach ([
            'Consulta de rutas urbanas y rurales de la municipalidad de El Alto.',
            'Visualizacion de recorridos sobre mapas digitales.',
            'Deteccion futura de cambios o desvios de ruta.',
            'Datos abiertos y colaborativos como base del crecimiento.',
          ] as $item)
            <li class="relative pl-7 before:absolute before:left-0 before:top-2 before:h-3.5 before:w-3.5 before:rounded-full before:bg-yellow before:content-['']">{{ $item }}</li>
          @endforeach
        </ul>
      </div>
      <aside class="rounded-xl border border-line bg-white p-4 shadow-[var(--shadow-rf)]" aria-label="Vista previa de rutas">
        <div class="relative min-h-[340px] overflow-hidden rounded-md bg-[#f7fbfb] [background-image:linear-gradient(90deg,rgba(11,114,133,0.1)_1px,transparent_1px),linear-gradient(rgba(11,114,133,0.1)_1px,transparent_1px)] [background-size:36px_36px]">
          <span class="absolute left-[9%] top-[18%] z-[2] whitespace-nowrap rounded-full bg-ink px-2.5 py-1.5 text-[0.82rem] font-extrabold text-white">Ceja</span>
          <span class="absolute right-[10%] top-[26%] z-[2] whitespace-nowrap rounded-full bg-ink px-2.5 py-1.5 text-[0.82rem] font-extrabold text-white">Rio Seco</span>
          <span class="absolute bottom-[20%] left-[18%] z-[2] whitespace-nowrap rounded-full bg-ink px-2.5 py-1.5 text-[0.82rem] font-extrabold text-white">Senkata</span>
          <span class="absolute bottom-[14%] right-[14%] z-[2] whitespace-nowrap rounded-full bg-ink px-2.5 py-1.5 text-[0.82rem] font-extrabold text-white">Villa Adela</span>
          <span class="absolute left-[16%] top-[30%] h-2 w-[66%] origin-left rotate-[11deg] rounded-full bg-green"></span>
          <span class="absolute bottom-[31%] left-[26%] h-2 w-[58%] origin-left rotate-[-14deg] rounded-full bg-red"></span>
        </div>
        <div class="mt-4 flex justify-between gap-3">
          <div class="flex-1 rounded-md bg-[#f5f7f4] p-3.5"><strong class="block text-lg">+20</strong><span class="block text-[0.82rem] text-muted">rutas objetivo</span></div>
          <div class="flex-1 rounded-md bg-[#f5f7f4] p-3.5"><strong class="block text-lg">GPS</strong><span class="block text-[0.82rem] text-muted">datos colaborativos</span></div>
          <div class="flex-1 rounded-md bg-[#f5f7f4] p-3.5"><strong class="block text-lg">Android</strong><span class="block text-[0.82rem] text-muted">primera version</span></div>
        </div>
      </aside>
    </section>

    {{-- COMMUNITY --}}
    <section id="comunidad" class="scroll-mt-24 bg-white px-4 py-[clamp(4rem,7vw,6rem)] sm:px-8 lg:px-16">
      <div class="mx-auto max-w-[1160px]">
        <div class="mb-8 max-w-[760px]">
          <p class="mb-3 text-xs font-extrabold uppercase text-green">Participacion ciudadana</p>
          <h2 class="mb-4 text-[clamp(2rem,4vw,3.4rem)] font-extrabold leading-[1.08]">Tu comentario puede mejorar una ruta.</h2>
          <p class="text-muted">
            Ruta Facil necesita opiniones reales: que linea usas, donde esperas, que desvio viste,
            o que informacion te gustaria tener antes de subir al transporte.
          </p>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
          <x-card>
            <h3 class="mb-2 text-lg font-bold">Pasajeros</h3>
            <p class="text-muted">Comparte rutas frecuentes, demoras y puntos donde suele faltar informacion.</p>
          </x-card>
          <x-card>
            <h3 class="mb-2 text-lg font-bold">Estudiantes</h3>
            <p class="text-muted">Encuentra conexiones mas claras para llegar a la universidad, instituto o colegio.</p>
          </x-card>
          <x-card>
            <h3 class="mb-2 text-lg font-bold">Visitantes</h3>
            <p class="text-muted">Reduce la incertidumbre al moverte por una ciudad que no conoces bien.</p>
          </x-card>
        </div>
      </div>
    </section>

    {{-- PANEL DE USUARIO: visible solo con sesion activa --}}
    @auth
    @php
      $authUser = auth()->user();
      $authRole = $authUser->roles->first();
      $rolSlug  = $authRole?->slug ?? 'usuario';
    @endphp
    <section id="mi-panel" class="scroll-mt-24 bg-[#eef5f0] px-4 py-[clamp(4rem,7vw,6rem)] sm:px-8 lg:px-16" aria-label="Panel de usuario">
      <div class="mx-auto max-w-[1160px]">
        <div class="mb-8 max-w-[760px]">
          <p class="mb-3 text-xs font-extrabold uppercase text-green">Mi panel</p>
          <h2 class="mb-4 text-[clamp(2rem,4vw,3.4rem)] font-extrabold leading-[1.08]">Bienvenido, {{ $authUser->name }}.</h2>
          <p class="flex items-center gap-2 text-muted">
            Sesion activa como <x-badge :role="$rolSlug">{{ $rolSlug }}</x-badge>
          </p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
          @if ($rolSlug === 'admin')
            <x-card>
              <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#fde9e6] font-black text-red">RPT</span>
              <h3 class="mb-2 text-lg font-bold">Reporte del sistema</h3>
              <p class="text-muted">Resumen automatizado de opiniones, proyectos y actividad ciudadana.</p>
              <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-button variant="primary" href="{{ route('reportes.resumen') }}">Ver reporte</x-button>
                <x-button href="{{ route('reportes.recorridos') }}">Recorridos</x-button>
              </div>
            </x-card>
            <x-card>
              <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#dff1e9] font-black text-green-dark">OPN</span>
              <h3 class="mb-2 text-lg font-bold">Gestionar opiniones</h3>
              <p class="text-muted">Revisa, edita y elimina cualquier opinion registrada por la ciudadania.</p>
              <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-button href="{{ route('opinions.index') }}">Ver CRUD</x-button>
              </div>
            </x-card>
            <x-card>
              <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#fff3cd] font-black text-[#7a5800]">NEW</span>
              <h3 class="mb-2 text-lg font-bold">Nueva opinion</h3>
              <p class="text-muted">Registra una nueva opinion ciudadana como administrador.</p>
              <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-button href="{{ route('opinions.create') }}">Crear</x-button>
              </div>
            </x-card>
            <x-card>
              <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#fde9e6] font-black text-red">LOG</span>
              <h3 class="mb-2 text-lg font-bold">Logs de seguridad</h3>
              <p class="text-muted">Auditoría de inicios de sesión, intentos fallidos y accesos denegados.</p>
              <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-button href="{{ route('seguridad.logs') }}">Ver auditoría</x-button>
              </div>
            </x-card>
          @elseif ($rolSlug === 'moderador')
            <x-card>
              <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#fff3cd] font-black text-[#7a5800]">RPT</span>
              <h3 class="mb-2 text-lg font-bold">Reporte del sistema</h3>
              <p class="text-muted">Resumen de opiniones, proyectos y actividad. Los moderadores tambien pueden consultarlo.</p>
              <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-button variant="primary" href="{{ route('reportes.resumen') }}">Ver reporte</x-button>
                <x-button href="{{ route('reportes.recorridos') }}">Recorridos</x-button>
              </div>
            </x-card>
            <x-card>
              <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#fff3cd] font-black text-[#7a5800]">MOD</span>
              <h3 class="mb-2 text-lg font-bold">Moderar opiniones</h3>
              <p class="text-muted">Revisa y edita las opiniones ciudadanas pendientes. No puedes eliminarlas.</p>
              <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-button href="{{ route('opinions.index') }}">Ver opiniones</x-button>
              </div>
            </x-card>
            <x-card>
              <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#dff1e9] font-black text-green-dark">NEW</span>
              <h3 class="mb-2 text-lg font-bold">Agregar opinion</h3>
              <p class="text-muted">Registra una nueva opinion en nombre de la comunidad.</p>
              <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-button href="{{ route('opinions.create') }}">Crear opinion</x-button>
              </div>
            </x-card>
          @else
            <x-card>
              <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#dff1e9] font-black text-green-dark">OPN</span>
              <h3 class="mb-2 text-lg font-bold">Opiniones ciudadanas</h3>
              <p class="text-muted">Consulta las opiniones registradas sobre las rutas de transporte.</p>
              <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-button href="{{ route('opinions.index') }}">Ver todas</x-button>
              </div>
            </x-card>
            <x-card>
              <span class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-md bg-[#dff1e9] font-black text-green-dark">NEW</span>
              <h3 class="mb-2 text-lg font-bold">Dejar mi opinion</h3>
              <p class="text-muted">Comparte tu experiencia sobre una ruta y ayuda a mejorar la informacion.</p>
              <div class="mt-4 flex flex-wrap items-center gap-2.5">
                <x-button variant="primary" href="{{ route('opinions.create') }}">Crear opinion</x-button>
              </div>
            </x-card>
          @endif
        </div>
      </div>
    </section>
    @endauth

    {{-- FEEDBACK / ULTIMAS OPINIONES --}}
    <section id="opinar" class="mx-auto grid max-w-[1160px] scroll-mt-24 items-start gap-[clamp(2rem,5vw,4rem)] px-4 py-[clamp(4rem,7vw,6rem)] md:grid-cols-[minmax(0,1fr)_minmax(320px,0.78fr)]">
      <div>
        <p class="mb-3 text-xs font-extrabold uppercase text-green">Opiniones ciudadanas</p>
        <h2 class="mb-4 text-[clamp(2rem,4vw,3.4rem)] font-extrabold leading-[1.08]">Tu reporte ayuda a mejorar las rutas de El Alto.</h2>
        <p class="text-muted">
          Cada opinion se guarda y queda disponible para que la comunidad y los moderadores
          puedan revisarla, clasificarla y usarla para mejorar la informacion de transporte.
        </p>
      </div>
      <x-card class="grid gap-4">
        <h3 class="text-lg font-bold">Ultimas opiniones</h3>
        @forelse ($latestOpinions as $opinion)
          <article class="border-b border-line pb-4 last:border-b-0">
            <x-badge color="green">{{ $opinion->route }}</x-badge>
            <h3 class="mb-1 mt-2 text-base font-bold">{{ $opinion->name }}</h3>
            <p class="text-muted">{{ $opinion->message }}</p>
          </article>
        @empty
          <p class="rounded-lg border border-dashed border-line p-4 text-muted">Aun no hay opiniones registradas. Crea la primera para probar el CRUD.</p>
        @endforelse
        <div class="flex flex-wrap gap-2.5">
          <x-button variant="primary" href="{{ route('opinions.create') }}">Crear opinion</x-button>
          <x-button href="{{ route('opinions.index') }}">Ver CRUD completo</x-button>
        </div>
      </x-card>
    </section>

    {{-- ANDROID --}}
    <section id="android" class="mx-auto mb-12 grid max-w-[1160px] scroll-mt-24 items-center gap-[clamp(2rem,5vw,4rem)] rounded-xl bg-ink p-[clamp(2rem,4vw,3rem)] text-white md:grid-cols-[minmax(0,1fr)_minmax(320px,0.78fr)]">
      <div>
        <p class="mb-3 text-xs font-extrabold uppercase text-yellow">Disponible primero en Android</p>
        <h2 class="mb-4 text-[clamp(2rem,4vw,3.4rem)] font-extrabold leading-[1.08]">Una app movil ligera para consultar rutas desde cualquier lugar.</h2>
        <p class="text-white/75">
          La primera version estara enfocada en Android, con mapas, busqueda de rutas,
          tiempos estimados y participacion colaborativa de usuarios.
        </p>
      </div>
      <div class="rounded-lg border border-line bg-surface p-6 text-ink shadow-[var(--shadow-rf)]">
        <x-badge color="green">Android</x-badge>
        <h3 class="mb-2 mt-3 text-lg font-bold">Ruta Facil App</h3>
        <p class="text-muted">
          Registrate para participar con tu opinion, o revisa el codigo fuente
          de la app movil directamente en GitHub.
        </p>
        <div class="mt-4 flex flex-wrap items-center gap-2.5">
          @guest
            <x-button variant="primary" href="{{ route('register') }}">Quiero participar</x-button>
          @else
            <x-button variant="primary" href="{{ route('opinions.create') }}">Dejar mi opinion</x-button>
          @endguest
          <x-button href="https://github.com/Nobody2077/mi_app" target="_blank" rel="noopener noreferrer">Ver app en GitHub</x-button>
        </div>
      </div>
    </section>
  </main>
@endsection
