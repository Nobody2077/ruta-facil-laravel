{{-- Estilos de la firma visual de auth: la red de teleferico (login + registro) --}}
<style>
  .rf-cable {
    position: absolute;
    left: 0;
    top: 0.35rem;
    bottom: 0.35rem;
    width: 3px;
    border-radius: 999px;
    background: linear-gradient(180deg, #d6275e 0%, #f4b41a 38%, #ef6f3c 70%, #0e9c9c 100%);
    transform-origin: top;
    transform: scaleY(0);
    animation: rf-subir 0.9s cubic-bezier(0.22, 1, 0.36, 1) forwards;
  }
  .rf-estacion {
    position: relative;
    display: grid;
    grid-template-columns: auto 1fr;
    grid-template-areas: "dot rotulo" "dot sub";
    align-items: center;
    column-gap: 0.85rem;
    opacity: 0;
    transform: translateX(-6px);
    animation: rf-aparece 0.45s ease forwards;
    animation-delay: calc(0.35s + var(--i) * 0.16s);
  }
  .rf-dot {
    grid-area: dot;
    width: 0.85rem;
    height: 0.85rem;
    margin-left: -0.78rem;
    border-radius: 999px;
    background: var(--c);
    box-shadow: 0 0 0 4px #1b1320;
  }
  .rf-dot--activa {
    width: 1.15rem;
    height: 1.15rem;
    margin-left: -0.95rem;
    box-shadow: 0 0 0 4px #1b1320, 0 0 0 7px color-mix(in srgb, var(--c) 45%, transparent);
  }
  .rf-rotulo {
    grid-area: rotulo;
    font-size: 0.95rem;
    font-weight: 800;
    letter-spacing: 0.01em;
  }
  .rf-sub {
    grid-area: sub;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.5);
  }
  .rf-estacion--activa .rf-rotulo { color: #fff; }
  .rf-estacion--activa .rf-sub { color: var(--yellow, #f4b41a); }

  @keyframes rf-subir { to { transform: scaleY(1); } }
  @keyframes rf-aparece { to { opacity: 1; transform: translateX(0); } }

  @media (prefers-reduced-motion: reduce) {
    .rf-cable { transform: scaleY(1); animation: none; }
    .rf-estacion { opacity: 1; transform: none; animation: none; }
  }
</style>
