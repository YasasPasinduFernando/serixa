<?php

declare(strict_types=1);

/**
 * SERIXA charts progressive enhancement module.
 *
 * Requires framework serixa.js to be loaded first (provides Serixa.module / boot).
 * Apps must include both scripts so the `chart` module is registered before boot
 * mounts [data-serixa-chart] elements. No virtual DOM — enhances existing SVG.
 */
(function (global) {
  'use strict';

  if (!global.Serixa || !Serixa.module) {
    return;
  }

  function parseConfig(el) {
    var raw = el.getAttribute('data-serixa-chart-config');
    if (!raw) {
      return {};
    }
    try {
      return JSON.parse(raw);
    } catch (err) {
      return {};
    }
  }

  function liveRegion(el) {
    return el.querySelector('[data-serixa-chart-live]');
  }

  function announce(el, text) {
    var live = liveRegion(el);
    if (!live) {
      return;
    }
    live.textContent = text || '';
  }

  function tipText(point) {
    var label = point.getAttribute('data-label') || '';
    var value = point.getAttribute('data-value') || '';
    var aria = point.getAttribute('aria-label');
    if (aria) {
      return aria;
    }
    if (label && value) {
      return label + ': ' + value;
    }
    return value || label || '';
  }

  function clearHighlight(root) {
    root.querySelectorAll('.charts-point-active, .charts-series-active').forEach(function (node) {
      node.classList.remove('charts-point-active');
      node.classList.remove('charts-series-active');
    });
  }

  function highlight(point) {
    point.classList.add('charts-point-active');
    var series = point.getAttribute('data-series-index');
    if (series !== null) {
      var parent = point.closest('[data-serixa-chart]');
      if (parent) {
        parent.querySelectorAll('[data-series-index="' + series + '"]').forEach(function (node) {
          node.classList.add('charts-series-active');
        });
      }
    }
  }

  function pointsOf(el) {
    return Array.prototype.slice.call(el.querySelectorAll('.charts-point, [data-serixa-chart] path.charts-slice, path.charts-point'));
  }

  function bindChart(el) {
    if (el.getAttribute('data-serixa-module-bound') === '1') {
      return;
    }
    el.setAttribute('data-serixa-module-bound', '1');

    var config = parseConfig(el);
    var tooltipEnabled = !config.tooltip || config.tooltip.enabled !== false;
    var points = pointsOf(el);
    var index = -1;

    points.forEach(function (point) {
      if (!point.hasAttribute('tabindex')) {
        point.setAttribute('tabindex', '0');
      }

      function activate() {
        clearHighlight(el);
        highlight(point);
        if (tooltipEnabled) {
          announce(el, tipText(point));
        }
        index = points.indexOf(point);
      }

      point.addEventListener('mouseenter', activate);
      point.addEventListener('focus', activate);
      point.addEventListener('mouseleave', function () {
        clearHighlight(el);
        announce(el, '');
      });
      point.addEventListener('blur', function () {
        clearHighlight(el);
      });
    });

    el.addEventListener('keydown', function (e) {
      if (points.length === 0) {
        return;
      }
      if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft' && e.key !== 'ArrowDown' && e.key !== 'ArrowUp') {
        return;
      }
      e.preventDefault();
      if (index < 0) {
        index = 0;
      } else if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
        index = (index + 1) % points.length;
      } else {
        index = (index - 1 + points.length) % points.length;
      }
      points[index].focus();
    });
  }

  Serixa.module('chart', {
    mount: function (root) {
      root.querySelectorAll('[data-serixa-chart]').forEach(bindChart);
    }
  });
})(typeof window !== 'undefined' ? window : this);
