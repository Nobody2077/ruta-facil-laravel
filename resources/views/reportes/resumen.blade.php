@extends('layouts.app')

@section('title', 'Reporte del sistema | Ruta Facil')

@section('content')
<main class="page-main">
  <div class="page-hero">
    <p class="eyebrow">Panel de administracion</p>
    <h2>Reporte del sistema</h2>
    <p>Resumen automatizado de opiniones, proyectos y actividad ciudadana en tiempo real.</p>
    <a href="{{ route('home') }}" style="color: var(--green); font-weight: 800;">&larr; Volver al inicio</a>
  </div>

  <div class="section" style="padding-top: 0;">

    {{-- Totales principales --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
      <div class="feature-card" style="text-align: center; padding: 2rem 1.25rem;">
        <p class="eyebrow">Total</p>
        <span style="font-size: 3.5rem; font-weight: 900; color: var(--ink); line-height: 1;">
          {{ $data['total_opinions'] }}
        </span>
        <p style="margin: 0.5rem 0 0; font-weight: 700; color: var(--muted);">Opiniones ciudadanas</p>
      </div>
      <div class="feature-card" style="text-align: center; padding: 2rem 1.25rem;">
        <p class="eyebrow">Total</p>
        <span style="font-size: 3.5rem; font-weight: 900; color: var(--ink); line-height: 1;">
          {{ $data['total_comments'] }}
        </span>
        <p style="margin: 0.5rem 0 0; font-weight: 700; color: var(--muted);">Comentarios</p>
      </div>
    </div>

    {{-- Opiniones por estado y por proyecto --}}
    <div style="display: grid; grid-template-columns: minmax(0,1fr) minmax(0,1.5fr); gap: 1.5rem; margin-bottom: 1.5rem;">

      <div class="feature-card" style="padding: 1.5rem;">
        <p class="eyebrow">Desglose</p>
        <h3>Opiniones por estado</h3>
        @forelse ($data['opinions_by_status'] as $estado => $total)
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid var(--line);">
            <span class="tag">{{ $estado }}</span>
            <strong style="font-size: 1.2rem;">{{ $total }}</strong>
          </div>
        @empty
          <p class="empty-state">Sin datos.</p>
        @endforelse
      </div>

      <div class="feature-card" style="padding: 1.5rem;">
        <p class="eyebrow">Ranking</p>
        <h3>Opiniones por proyecto</h3>
        @forelse ($data['opinions_by_project'] as $i => $item)
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.65rem 0; border-bottom: 1px solid var(--line);">
            <span style="color: var(--muted); font-size: 0.85rem; margin-right: 0.5rem;">
              {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
            </span>
            <span style="flex: 1;">{{ $item['project'] }}</span>
            <strong>{{ $item['total'] }}</strong>
          </div>
        @empty
          <p class="empty-state">Sin datos.</p>
        @endforelse
      </div>

    </div>

    {{-- Proyectos por categoria --}}
    <div class="feature-card" style="padding: 1.5rem; margin-bottom: 3rem;">
      <p class="eyebrow">Distribucion</p>
      <h3>Proyectos por categoria</h3>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-top: 1rem;">
        @forelse ($data['projects_by_category'] as $item)
          <div style="padding: 1rem; border: 1px solid var(--line); border-radius: 0.45rem; text-align: center;">
            <strong style="font-size: 1.8rem; display: block;">{{ $item['total'] }}</strong>
            <span style="color: var(--muted); font-size: 0.88rem;">{{ $item['category'] }}</span>
          </div>
        @empty
          <p class="empty-state">Sin datos.</p>
        @endforelse
      </div>
    </div>

  </div>
</main>
@endsection
