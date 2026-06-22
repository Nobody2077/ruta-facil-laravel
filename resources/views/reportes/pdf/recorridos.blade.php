<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    * { font-family: DejaVu Sans, sans-serif; }
    body { color: #18222a; font-size: 12px; margin: 0; }
    .header { border-bottom: 3px solid #d6275e; padding-bottom: 10px; margin-bottom: 16px; }
    .brand { color: #d6275e; font-size: 20px; font-weight: bold; }
    .title { font-size: 16px; margin: 4px 0 0; }
    .muted { color: #5d6b75; font-size: 10px; }
    .stats { width: 100%; margin-bottom: 16px; }
    .stats td { width: 25%; padding: 5px; }
    .stat-box { border: 1px solid #dfe5e5; border-radius: 6px; padding: 8px; text-align: center; }
    .stat-num { font-size: 20px; font-weight: bold; color: #d6275e; }
    .stat-label { color: #5d6b75; font-size: 9px; }
    h2 { font-size: 13px; color: #b01b4c; border-bottom: 1px solid #dfe5e5; padding-bottom: 4px; margin: 14px 0 8px; }
    .recorrido { border: 1px solid #dfe5e5; border-radius: 6px; padding: 10px; margin-bottom: 10px; page-break-inside: avoid; }
    .rec-head { font-size: 13px; font-weight: bold; }
    .rec-meta { color: #5d6b75; font-size: 10px; margin: 2px 0 6px; }
    .codigo { display: inline-block; background: #dceef2; color: #0e9c9c; padding: 1px 6px; border-radius: 8px; font-size: 9px; font-weight: bold; }
    .inactivo { background: #fde9e6; color: #c44832; }
    table.paradas { width: 100%; border-collapse: collapse; }
    table.paradas th { background: #eef5f0; text-align: left; padding: 4px 6px; font-size: 10px; border-bottom: 1px solid #dfe5e5; }
    table.paradas td { padding: 4px 6px; border-bottom: 1px solid #f0f2f2; font-size: 11px; }
    .footer { margin-top: 18px; color: #9aa4ab; font-size: 9px; text-align: center; }
  </style>
</head>
<body>
  <div class="header">
    <div class="brand">Ruta Facil</div>
    <p class="title">Reporte de recorridos y sus paradas (cabecera-detalle)</p>
    <p class="muted">Generado el {{ $data['generado_en'] }}</p>
  </div>

  <table class="stats">
    <tr>
      <td><div class="stat-box"><div class="stat-num">{{ $data['total_recorridos'] }}</div><div class="stat-label">Recorridos</div></div></td>
      <td><div class="stat-box"><div class="stat-num">{{ $data['total_paradas'] }}</div><div class="stat-label">Paradas</div></div></td>
      <td><div class="stat-box"><div class="stat-num">{{ $data['recorridos_activos'] }}</div><div class="stat-label">Activos</div></div></td>
      <td><div class="stat-box"><div class="stat-num">{{ $data['promedio_paradas'] }}</div><div class="stat-label">Paradas/recorrido</div></div></td>
    </tr>
  </table>

  <h2>Detalle por recorrido</h2>
  @forelse ($data['detalle'] as $rec)
    <div class="recorrido">
      <div class="rec-head">
        {{ $rec['nombre'] }}
        <span class="codigo {{ $rec['activo'] ? '' : 'inactivo' }}">{{ $rec['codigo'] }}</span>
      </div>
      <div class="rec-meta">
        {{ $rec['origen'] }} &rarr; {{ $rec['destino'] }} ·
        {{ $rec['categoria'] }}
        @if ($rec['tarifa_bs']) · Tarifa: {{ $rec['tarifa_bs'] }} Bs @endif
        · {{ $rec['activo'] ? 'Activo' : 'Inactivo' }}
      </div>
      <table class="paradas">
        <thead><tr><th style="width:40px;">Orden</th><th>Parada</th><th>Referencia</th></tr></thead>
        <tbody>
          @forelse ($rec['paradas'] as $parada)
            <tr>
              <td>{{ $parada['orden'] }}</td>
              <td>{{ $parada['nombre'] }}</td>
              <td>{{ $parada['referencia'] ?? '—' }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="muted">Sin paradas registradas.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  @empty
    <p class="muted">No hay recorridos registrados.</p>
  @endforelse

  <div class="footer">Ruta Facil — Reporte cabecera-detalle generado automáticamente.</div>
</body>
</html>
