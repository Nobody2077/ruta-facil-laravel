@extends('layouts.app')

@section('title', 'Ruta Facil | Transporte publico de El Alto')

@section('content')
  <main>
    <section class="hero">
      <div class="hero-content">
        <p class="eyebrow">El Alto, Bolivia</p>
        <h1>Rutas urbanas y rurales mas claras para moverte mejor.</h1>
        <p class="hero-copy">
          Ruta Facil es una app movil para consultar recorridos del transporte publico,
          estimar tiempos de llegada y construir informacion confiable con ayuda de los usuarios.
        </p>
        <div class="hero-actions">
          <a class="button button-primary" href="#android">Quiero probar la app</a>
          <a class="button button-secondary" href="{{ route('opinions.create') }}">Dejar mi opinion</a>
        </div>
      </div>
    </section>

    <section class="section intro-band" aria-label="Resumen del proyecto">
      <div class="section-heading">
        <p class="eyebrow">Movilidad inteligente</p>
        <h2>Una guia pensada para la realidad del transporte alteno.</h2>
      </div>
      <div class="feature-grid">
        <article class="feature-card">
          <span class="feature-icon">01</span>
          <h3>Recorridos digitalizados</h3>
          <p>Mapas de minibuses, trufis y micros con rutas faciles de consultar desde el celular.</p>
        </article>
        <article class="feature-card">
          <span class="feature-icon">02</span>
          <h3>Tiempos estimados</h3>
          <p>Calculos basados en distancia, velocidad promedio y datos compartidos por pasajeros.</p>
        </article>
        <article class="feature-card">
          <span class="feature-icon">03</span>
          <h3>Reportes colaborativos</h3>
          <p>La comunidad puede avisar cambios, desvios y experiencias para mantener la informacion viva.</p>
        </article>
      </div>
    </section>

    <section class="section split-section" id="rutas">
      <div>
        <p class="eyebrow">Enfoque local</p>
        <h2>Primero El Alto: zonas, conexiones y rutas que la gente usa todos los dias.</h2>
        <p>
          La aplicacion nace para resolver una necesidad concreta: muchas rutas no estan
          documentadas de forma clara y los usuarios dependen de preguntar o aprender por experiencia.
        </p>
        <ul class="check-list">
          <li>Consulta de rutas urbanas y rurales de la municipalidad de El Alto.</li>
          <li>Visualizacion de recorridos sobre mapas digitales.</li>
          <li>Deteccion futura de cambios o desvios de ruta.</li>
          <li>Datos abiertos y colaborativos como base del crecimiento.</li>
        </ul>
      </div>
      <aside class="route-panel" aria-label="Vista previa de rutas">
        <div class="route-map">
          <span class="route-node node-a">Ceja</span>
          <span class="route-node node-b">Rio Seco</span>
          <span class="route-node node-c">Senkata</span>
          <span class="route-node node-d">Villa Adela</span>
          <span class="route-line line-one"></span>
          <span class="route-line line-two"></span>
        </div>
        <div class="route-stats">
          <div><strong>+20</strong><span>rutas objetivo</span></div>
          <div><strong>GPS</strong><span>datos colaborativos</span></div>
          <div><strong>Android</strong><span>primera version</span></div>
        </div>
      </aside>
    </section>

    <section class="section community-section" id="comunidad">
      <div class="section-heading">
        <p class="eyebrow">Participacion ciudadana</p>
        <h2>Tu comentario puede mejorar una ruta.</h2>
        <p>
          Ruta Facil necesita opiniones reales: que linea usas, donde esperas, que desvio viste,
          o que informacion te gustaria tener antes de subir al transporte.
        </p>
      </div>
      <div class="audience-grid">
        <article>
          <h3>Pasajeros</h3>
          <p>Comparte rutas frecuentes, demoras y puntos donde suele faltar informacion.</p>
        </article>
        <article>
          <h3>Estudiantes</h3>
          <p>Encuentra conexiones mas claras para llegar a la universidad, instituto o colegio.</p>
        </article>
        <article>
          <h3>Visitantes</h3>
          <p>Reduce la incertidumbre al moverte por una ciudad que no conoces bien.</p>
        </article>
      </div>
    </section>

    {{-- Panel de usuario: visible solo cuando hay sesion activa --}}
    @auth
    @php
      $authUser = auth()->user();
      $authRole = $authUser->roles->first();
      $rolSlug  = $authRole?->slug ?? 'usuario';
    @endphp
    <section class="section user-panel-section" id="mi-panel" aria-label="Panel de usuario">
      <div class="section-heading">
        <p class="eyebrow">Mi panel</p>
        <h2>Bienvenido, {{ $authUser->name }}.</h2>
        <p>
          Sesion activa como
          <span class="role-badge role-{{ $rolSlug }}" style="font-size: 0.85rem; vertical-align: middle;">
            {{ $rolSlug }}
          </span>
        </p>
      </div>

      <div class="feature-grid">
        @if ($rolSlug === 'admin')
          <article class="feature-card">
            <span class="feature-icon feature-icon-admin">RPT</span>
            <h3>Reporte del sistema</h3>
            <p>Resumen automatizado de opiniones, proyectos y actividad ciudadana.</p>
            <div class="card-actions">
              <a class="button button-primary" href="{{ route('reportes.resumen') }}">Ver reporte</a>
            </div>
          </article>
          <article class="feature-card">
            <span class="feature-icon">OPN</span>
            <h3>Gestionar opiniones</h3>
            <p>Revisa, edita y elimina cualquier opinion registrada por la ciudadania.</p>
            <div class="card-actions">
              <a class="button" href="{{ route('opinions.index') }}">Ver CRUD</a>
            </div>
          </article>
          <article class="feature-card">
            <span class="feature-icon" style="background: #fff3cd; color: #7a5800;">NEW</span>
            <h3>Nueva opinion</h3>
            <p>Registra una nueva opinion ciudadana como administrador.</p>
            <div class="card-actions">
              <a class="button" href="{{ route('opinions.create') }}">Crear</a>
            </div>
          </article>
        @elseif ($rolSlug === 'moderador')
          <article class="feature-card">
            <span class="feature-icon feature-icon-mod">RPT</span>
            <h3>Reporte del sistema</h3>
            <p>Resumen de opiniones, proyectos y actividad. Los moderadores tambien pueden consultarlo.</p>
            <div class="card-actions">
              <a class="button button-primary" href="{{ route('reportes.resumen') }}">Ver reporte</a>
            </div>
          </article>
          <article class="feature-card">
            <span class="feature-icon feature-icon-mod">MOD</span>
            <h3>Moderar opiniones</h3>
            <p>Revisa y edita las opiniones ciudadanas pendientes. No puedes eliminarlas.</p>
            <div class="card-actions">
              <a class="button" href="{{ route('opinions.index') }}">Ver opiniones</a>
            </div>
          </article>
          <article class="feature-card">
            <span class="feature-icon">NEW</span>
            <h3>Agregar opinion</h3>
            <p>Registra una nueva opinion en nombre de la comunidad.</p>
            <div class="card-actions">
              <a class="button" href="{{ route('opinions.create') }}">Crear opinion</a>
            </div>
          </article>
        @else
          <article class="feature-card">
            <span class="feature-icon">OPN</span>
            <h3>Opiniones ciudadanas</h3>
            <p>Consulta las opiniones registradas sobre las rutas de transporte.</p>
            <div class="card-actions">
              <a class="button" href="{{ route('opinions.index') }}">Ver todas</a>
            </div>
          </article>
          <article class="feature-card">
            <span class="feature-icon" style="background: #dff1e9; color: var(--green-dark);">NEW</span>
            <h3>Dejar mi opinion</h3>
            <p>Comparte tu experiencia sobre una ruta y ayuda a mejorar la informacion.</p>
            <div class="card-actions">
              <a class="button button-primary" href="{{ route('opinions.create') }}">Crear opinion</a>
            </div>
          </article>
        @endif
      </div>
    </section>
    @endauth

    <section class="section feedback-section" id="opinar">
      <div class="feedback-copy">
        <p class="eyebrow">Opiniones ciudadanas</p>
        <h2>Tu reporte ayuda a mejorar las rutas de El Alto.</h2>
        <p>
          Cada opinion se guarda y queda disponible para que la comunidad y los moderadores
          puedan revisarla, clasificarla y usarla para mejorar la informacion de transporte.
        </p>
      </div>
      <div class="feedback-form">
        <h3>Ultimas opiniones</h3>
        @forelse ($latestOpinions as $opinion)
          <article class="mini-opinion">
            <span class="tag">{{ $opinion->route }}</span>
            <h3>{{ $opinion->name }}</h3>
            <p>{{ $opinion->message }}</p>
          </article>
        @empty
          <p class="empty-state">Aun no hay opiniones registradas. Crea la primera para probar el CRUD.</p>
        @endforelse
        <a class="button button-primary" href="{{ route('opinions.create') }}">Crear opinion</a>
        <a class="button" href="{{ route('opinions.index') }}">Ver CRUD completo</a>
      </div>
    </section>

    <section class="section android-section" id="android">
      <div>
        <p class="eyebrow">Disponible primero en Android</p>
        <h2>Una app movil ligera para consultar rutas desde cualquier lugar.</h2>
        <p>
          La primera version estara enfocada en Android, con mapas, busqueda de rutas,
          tiempos estimados y participacion colaborativa de usuarios.
        </p>
      </div>
      <div class="download-card">
        <span class="android-badge">Android</span>
        <h3>Ruta Facil App</h3>
        <p>
          Registrate para participar con tu opinion, o revisa el codigo fuente
          de la app movil directamente en GitHub.
        </p>
        <div class="card-actions" style="margin-top: 1rem;">
          @guest
            <a class="button button-primary" href="{{ route('register') }}">Quiero participar</a>
          @else
            <a class="button button-primary" href="{{ route('opinions.create') }}">Dejar mi opinion</a>
          @endguest
          <a class="button" href="https://github.com/Nobody2077/mi_app" target="_blank" rel="noopener noreferrer">
            Ver app en GitHub
          </a>
        </div>
      </div>
    </section>
  </main>
@endsection
