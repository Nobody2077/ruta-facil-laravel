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

    <section class="section feedback-section" id="opinar">
      <div class="feedback-copy">
        <p class="eyebrow">CRUD de opiniones</p>
        <h2>Comentarios conectados a MySQL y phpMyAdmin.</h2>
        <p>
          A diferencia de la version estatica, ahora cada opinion se registra en la base de datos
          `ruta_facil` mediante Laravel, controlador, modelo, migracion y vistas Blade.
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
        <p>Version inicial en desarrollo. Puedes registrar interes y dejar sugerencias desde esta pagina.</p>
        <a class="button button-primary" href="{{ route('opinions.create') }}">Quiero participar</a>
      </div>
    </section>
  </main>
@endsection
