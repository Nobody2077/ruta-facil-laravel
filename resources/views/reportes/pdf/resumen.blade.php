<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <style>
    * { font-family: DejaVu Sans, sans-serif; }
    body { color: #18222a; font-size: 12px; margin: 0; }
    .header { border-bottom: 3px solid #d6275e; padding-bottom: 10px; margin-bottom: 18px; }
    .brand { color: #d6275e; font-size: 20px; font-weight: bold; }
    .title { font-size: 16px; margin: 4px 0 0; }
    .muted { color: #5d6b75; font-size: 10px; }
    .totals { width: 100%; margin-bottom: 18px; }
    .totals td { width: 50%; padding: 6px; }
    .total-box { border: 1px solid #dfe5e5; border-radius: 6px; padding: 12px; text-align: center; }
    .total-num { font-size: 30px; font-weight: bold; color: #d6275e; }
    .total-label { color: #5d6b75; font-size: 11px; }
    h2 { font-size: 13px; color: #b01b4c; border-bottom: 1px solid #dfe5e5; padding-bottom: 4px; margin: 18px 0 8px; }
    table.data { width: 100%; border-collapse: collapse; }
    table.data th { background: #eef5f0; text-align: left; padding: 6px 8px; font-size: 11px; border-bottom: 1px solid #dfe5e5; }
    table.data td { padding: 6px 8px; border-bottom: 1px solid #eef0f0; }
    table.data td.num { text-align: right; font-weight: bold; }
    .footer { margin-top: 24px; color: #9aa4ab; font-size: 9px; text-align: center; }
  </style>
</head>
<body>
  <div class="header">
    <div class="brand">Ruta Facil</div>
    <p class="title">Reporte del sistema — Resumen general</p>
    <p class="muted">Generado el {{ now()->format('d/m/Y H:i') }}</p>
  </div>

  <table class="totals">
    <tr>
      <td>
        <div class="total-box">
          <div class="total-num">{{ $data['total_opinions'] }}</div>
          <div class="total-label">Opiniones ciudadanas</div>
        </div>
      </td>
      <td>
        <div class="total-box">
          <div class="total-num">{{ $data['total_comments'] }}</div>
          <div class="total-label">Comentarios</div>
        </div>
      </td>
    </tr>
  </table>

  <h2>Opiniones por estado</h2>
  <table class="data">
    <thead><tr><th>Estado</th><th style="text-align:right;">Total</th></tr></thead>
    <tbody>
      @forelse ($data['opinions_by_status'] as $estado => $total)
        <tr><td>{{ ucfirst($estado) }}</td><td class="num">{{ $total }}</td></tr>
      @empty
        <tr><td colspan="2" class="muted">Sin datos.</td></tr>
      @endforelse
    </tbody>
  </table>

  <h2>Opiniones por proyecto</h2>
  <table class="data">
    <thead><tr><th>#</th><th>Proyecto</th><th style="text-align:right;">Opiniones</th></tr></thead>
    <tbody>
      @forelse ($data['opinions_by_project'] as $i => $item)
        <tr><td>{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</td><td>{{ $item['project'] }}</td><td class="num">{{ $item['total'] }}</td></tr>
      @empty
        <tr><td colspan="3" class="muted">Sin datos.</td></tr>
      @endforelse
    </tbody>
  </table>

  <h2>Proyectos por categoría</h2>
  <table class="data">
    <thead><tr><th>Categoría</th><th style="text-align:right;">Proyectos</th></tr></thead>
    <tbody>
      @forelse ($data['projects_by_category'] as $item)
        <tr><td>{{ $item['category'] }}</td><td class="num">{{ $item['total'] }}</td></tr>
      @empty
        <tr><td colspan="2" class="muted">Sin datos.</td></tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">Ruta Facil — Movilidad urbana inteligente para El Alto, Bolivia. Documento generado automáticamente.</div>
</body>
</html>
