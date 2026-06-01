/* ==========================================================================
   Вариант V4 «Bento Grid» — доп. микроанимации поверх app.js.
   Group-agnostic, без дублирования логики app.js (slider/modals/toasts/chips/
   counters/date-mask). Только: каскадная задержка появления плиток и мягкий
   hover-tilt. Везде guard'ы и учёт prefers-reduced-motion.
   ========================================================================== */
(function () {
  'use strict';

  var reduce = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return; // уважение к настройке — никаких доп. движений

  /* --- Каскадное появление плиток внутри каждой bento-сетки --- */
  var grids = document.querySelectorAll('.bento');
  if (grids && grids.length) {
    grids.forEach(function (grid) {
      var tiles = grid.querySelectorAll('.reveal');
      tiles.forEach(function (el, i) {
        // лёгкая ступенчатая задержка, с потолком чтобы хвост не «висел»
        var delay = Math.min(i * 0.06, 0.42);
        el.style.setProperty('--reveal-delay', delay + 's');
      });
    });
  }

  /* --- Мягкий hover-tilt для плиток с [data-tilt] --- */
  var tiltEls = document.querySelectorAll('[data-tilt]');
  if (!tiltEls || !tiltEls.length) return;

  // тонкий указатель (мышь) — на тач-устройствах tilt не нужен
  var fine = !window.matchMedia || window.matchMedia('(pointer: fine)').matches;
  if (!fine) return;

  var MAX = 5; // максимальный наклон в градусах — держим мягко

  tiltEls.forEach(function (el) {
    var raf = null;

    function apply(e) {
      var r = el.getBoundingClientRect();
      if (!r.width || !r.height) return;
      var px = (e.clientX - r.left) / r.width;  // 0..1
      var py = (e.clientY - r.top) / r.height;  // 0..1
      var ry = (px - 0.5) * 2 * MAX;            // поворот по Y
      var rx = (0.5 - py) * 2 * MAX;            // поворот по X
      if (raf) cancelAnimationFrame(raf);
      raf = requestAnimationFrame(function () {
        el.style.transform =
          'perspective(900px) rotateX(' + rx.toFixed(2) + 'deg) rotateY(' +
          ry.toFixed(2) + 'deg) translateY(-6px)';
      });
    }

    function reset() {
      if (raf) cancelAnimationFrame(raf);
      el.style.transform = '';
    }

    el.addEventListener('mouseenter', function () { el.classList.add('is-tilting'); });
    el.addEventListener('mousemove', apply);
    el.addEventListener('mouseleave', function () {
      el.classList.remove('is-tilting');
      reset();
    });
  });
})();
