{{-- Capa de animaciones de la home (mega). Todo se desactiva con prefers-reduced-motion. --}}
<style>
  /* ===== Reveals al hacer scroll (solo activos en modo animado) ===== */
  #rf-home.rf-anim .rf-reveal {
    opacity: 0;
    transform: translateY(38px);
    transition: opacity 0.7s cubic-bezier(0.22, 1, 0.36, 1), transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    transition-delay: var(--d, 0ms);
    will-change: opacity, transform;
  }
  #rf-home.rf-anim .rf-reveal.rf-left { transform: translateX(-46px); }
  #rf-home.rf-anim .rf-reveal.rf-right { transform: translateX(46px); }
  #rf-home.rf-anim .rf-reveal.rf-scale { transform: scale(0.88); }
  #rf-home.rf-anim .rf-reveal.rf-in { opacity: 1; transform: none; }

  /* ===== Hero: entrada de los items ===== */
  #rf-home.rf-anim .rf-hero-item {
    opacity: 0;
    transform: translateY(42px);
    animation: rf-rise 0.95s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    animation-delay: var(--d, 0ms);
  }
  @keyframes rf-rise { to { opacity: 1; transform: none; } }

  /* ===== Hero: fondo ken-burns ===== */
  .rf-hero-bg { transform: scale(1.06); }
  #rf-home.rf-anim .rf-hero-bg { animation: rf-kenburns 24s ease-in-out infinite alternate; }
  @keyframes rf-kenburns { from { transform: scale(1.06); } to { transform: scale(1.2) translateY(-2%); } }

  /* ===== Hero: malla de color animada ===== */
  .rf-hero-mesh {
    background:
      radial-gradient(40% 50% at 15% 30%, rgba(241,184,45,0.22), transparent 70%),
      radial-gradient(45% 45% at 85% 20%, rgba(14,143,104,0.22), transparent 70%),
      radial-gradient(50% 50% at 70% 90%, rgba(11,114,133,0.25), transparent 70%);
    background-size: 200% 200%;
    transform: translate(var(--mx, 0), var(--my, 0));
    transition: transform 0.3s ease-out;
  }
  #rf-home.rf-anim .rf-hero-mesh { animation: rf-mesh 18s ease-in-out infinite alternate; }
  @keyframes rf-mesh {
    0% { background-position: 0% 0%; }
    100% { background-position: 100% 100%; }
  }

  /* ===== Hero: cable + cabina de teleferico ===== */
  .rf-cable-path { opacity: 0.7; }
  #rf-home.rf-anim .rf-cable-path { animation: rf-cable-dash 3s linear infinite; }
  @keyframes rf-cable-dash { to { stroke-dashoffset: -28; } }

  .rf-cabin {
    position: absolute;
    top: 0; left: 0;
    width: 22px; height: 14px;
    border-radius: 4px;
    background: linear-gradient(180deg, #f1b82d, #d99e16);
    box-shadow: 0 4px 14px rgba(0,0,0,0.4);
    opacity: 0;
  }
  .rf-cabin::before {
    content: "";
    position: absolute;
    left: 50%; top: -6px;
    width: 2px; height: 6px;
    background: rgba(255,255,255,0.6);
    transform: translateX(-50%);
  }
  #rf-home.rf-anim .rf-cabin {
    opacity: 1;
    offset-path: path("M -20 130 C 250 60, 520 220, 1020 120");
    animation: rf-cabin-move 9s linear infinite;
  }
  @supports not (offset-path: path("M 0 0")) { .rf-cabin { display: none; } }
  @keyframes rf-cabin-move {
    from { offset-distance: 0%; }
    to { offset-distance: 100%; }
  }

  /* ===== Hero: particulas ===== */
  .rf-dot-fx {
    position: absolute;
    width: 6px; height: 6px;
    border-radius: 999px;
    background: rgba(255,255,255,0.5);
    opacity: 0;
  }
  #rf-home.rf-anim .rf-dot-fx { animation: rf-float 7s ease-in-out infinite; }
  .rf-dot-fx:nth-child(1) { left: 12%; top: 70%; background: rgba(241,184,45,0.7); animation-duration: 8s; }
  .rf-dot-fx:nth-child(2) { left: 28%; top: 40%; animation-duration: 6.5s; animation-delay: 0.6s; }
  .rf-dot-fx:nth-child(3) { left: 44%; top: 80%; background: rgba(14,143,104,0.7); animation-duration: 9s; animation-delay: 1.2s; }
  .rf-dot-fx:nth-child(4) { left: 60%; top: 35%; animation-duration: 7.5s; animation-delay: 0.3s; }
  .rf-dot-fx:nth-child(5) { left: 72%; top: 65%; background: rgba(11,114,133,0.8); animation-duration: 8.5s; animation-delay: 1.5s; }
  .rf-dot-fx:nth-child(6) { left: 84%; top: 45%; animation-duration: 6s; animation-delay: 0.9s; }
  .rf-dot-fx:nth-child(7) { left: 20%; top: 55%; background: rgba(241,184,45,0.6); animation-duration: 9.5s; animation-delay: 0.2s; }
  .rf-dot-fx:nth-child(8) { left: 52%; top: 60%; animation-duration: 7s; animation-delay: 1.8s; }
  .rf-dot-fx:nth-child(9) { left: 90%; top: 75%; background: rgba(14,143,104,0.6); animation-duration: 8s; animation-delay: 1s; }
  @keyframes rf-float {
    0% { opacity: 0; transform: translateY(0) scale(0.8); }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { opacity: 0; transform: translateY(-60px) scale(1.2); }
  }

  /* ===== Indicador de scroll ===== */
  #rf-home.rf-anim .rf-scroll-ind { animation: rf-fade-in 1s ease 1.2s both; }
  #rf-home.rf-anim .rf-scroll-bead { animation: rf-bead 1.6s ease-in-out infinite; }
  @keyframes rf-fade-in { from { opacity: 0; } to { opacity: 1; } }
  @keyframes rf-bead { 0%,100% { transform: translateY(0); opacity: 1; } 50% { transform: translateY(8px); opacity: 0.3; } }

  /* ===== Hover de tarjetas ===== */
  .rf-card-hover { transition: transform 0.3s ease, box-shadow 0.3s ease; }
  #rf-home.rf-anim .rf-card-hover:hover {
    transform: translateY(-8px);
    box-shadow: 0 26px 50px rgba(24,34,42,0.18);
  }

  /* ===== CTA con brillo ===== */
  #rf-home.rf-anim .rf-cta { position: relative; overflow: hidden; }
  #rf-home.rf-anim .rf-cta::after {
    content: "";
    position: absolute;
    top: 0; left: -120%;
    width: 60%; height: 100%;
    background: linear-gradient(120deg, transparent, rgba(255,255,255,0.4), transparent);
    transform: skewX(-20deg);
    animation: rf-shine 4s ease-in-out infinite;
  }
  @keyframes rf-shine { 0%,60% { left: -120%; } 100% { left: 160%; } }

  /* ===== Mini-mapa: lineas, vehiculo y paradas ===== */
  .rf-line { clip-path: inset(0 0 0 0); transition: clip-path 0.9s cubic-bezier(0.22, 1, 0.36, 1) 0.3s; }
  #rf-home.rf-anim .rf-reveal:not(.rf-in) .rf-line { clip-path: inset(0 100% 0 0); }

  .rf-vehicle { left: 0; }
  #rf-home.rf-anim .rf-reveal.rf-in .rf-vehicle { animation: rf-vehicle-move 4.5s ease-in-out 1.2s infinite; }
  @keyframes rf-vehicle-move { 0% { left: 0%; } 50% { left: calc(100% - 14px); } 100% { left: 0%; } }

  #rf-home.rf-anim .rf-stop { position: absolute; }
  #rf-home.rf-anim .rf-reveal.rf-in .rf-stop { animation: rf-stop-pop 0.5s ease backwards; }
  #rf-home.rf-anim .rf-reveal.rf-in .rf-stop:nth-of-type(1) { animation-delay: 0.5s; }
  #rf-home.rf-anim .rf-reveal.rf-in .rf-stop:nth-of-type(2) { animation-delay: 0.7s; }
  #rf-home.rf-anim .rf-reveal.rf-in .rf-stop:nth-of-type(3) { animation-delay: 0.9s; }
  #rf-home.rf-anim .rf-reveal.rf-in .rf-stop:nth-of-type(4) { animation-delay: 1.1s; }
  @keyframes rf-stop-pop { from { opacity: 0; transform: scale(0.5); } to { opacity: 1; transform: scale(1); } }

  /* ===== Respeto total a quien prefiere menos movimiento ===== */
  @media (prefers-reduced-motion: reduce) {
    .rf-hero-bg { transform: none; }
    .rf-cabin, .rf-dot-fx, .rf-hero-mesh { animation: none !important; }
  }
</style>

@push('scripts')
<script>
  (function () {
    const home = document.getElementById('rf-home');
    if (!home) return;

    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Reparte un delay incremental a los .rf-reveal dentro de cada grupo [data-stagger]
    home.querySelectorAll('[data-stagger]').forEach(function (group) {
      group.querySelectorAll('.rf-reveal').forEach(function (el, i) {
        el.style.setProperty('--d', (i * 90) + 'ms');
      });
    });

    // Sin animaciones: mostrar todo y fijar contadores en su valor final
    if (reduce) {
      home.querySelectorAll('[data-counter]').forEach(function (el) {
        el.textContent = el.dataset.counter;
      });
      return;
    }

    // Activa el modo animado (el CSS solo oculta los reveals cuando existe .rf-anim)
    home.classList.add('rf-anim');

    // Observa los reveals y dispara contadores
    const io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('rf-in');
        if (entry.target.hasAttribute('data-counter')) animateCounter(entry.target);
        io.unobserve(entry.target);
      });
    }, { threshold: 0.15, rootMargin: '0px 0px -8% 0px' });

    home.querySelectorAll('.rf-reveal').forEach(function (el) { io.observe(el); });
    home.querySelectorAll('[data-counter]').forEach(function (el) {
      if (!el.classList.contains('rf-reveal')) io.observe(el);
    });

    function animateCounter(el) {
      const raw = el.dataset.counter;
      const target = parseInt(raw.replace(/\D/g, ''), 10);
      if (isNaN(target)) { el.textContent = raw; return; }
      const prefix = /^\D+/.exec(raw);
      const pre = prefix ? prefix[0] : '';
      const dur = 1300;
      const start = performance.now();
      (function tick(now) {
        const p = Math.min((now - start) / dur, 1);
        const eased = 1 - Math.pow(1 - p, 3);
        el.textContent = pre + Math.round(target * eased);
        if (p < 1) requestAnimationFrame(tick);
        else el.textContent = raw;
      })(start);
    }

    // Parallax suave en scroll
    const parallax = Array.prototype.slice.call(home.querySelectorAll('[data-parallax]'));
    let ticking = false;
    function onScroll() {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(function () {
        const y = window.scrollY;
        parallax.forEach(function (el) {
          const speed = parseFloat(el.dataset.parallax) || 0.15;
          el.style.transform = 'translate3d(0,' + (y * speed) + 'px,0)';
        });
        ticking = false;
      });
    }
    window.addEventListener('scroll', onScroll, { passive: true });

    // Parallax del puntero sobre el hero (malla de color)
    const hero = document.getElementById('rf-hero');
    if (hero && window.matchMedia('(pointer:fine)').matches) {
      hero.addEventListener('pointermove', function (ev) {
        const r = hero.getBoundingClientRect();
        const cx = (ev.clientX - r.left) / r.width - 0.5;
        const cy = (ev.clientY - r.top) / r.height - 0.5;
        hero.style.setProperty('--mx', (cx * 16).toFixed(1) + 'px');
        hero.style.setProperty('--my', (cy * 16).toFixed(1) + 'px');
      });
    }
  })();
</script>
@endpush
