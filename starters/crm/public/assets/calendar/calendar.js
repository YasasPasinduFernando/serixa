/**
 * SERIXA calendar progressive enhancement module.
 *
 * Requires framework serixa.js first. Include serixa.js then calendar.js (defer).
 * Enhances existing SSR markup — no hydration, no virtual DOM.
 */
(function (global) {
  'use strict';

  if (!global.Serixa || !Serixa.module) {
    return;
  }

  function parseConfig(el) {
    var raw = el.getAttribute('data-serixa-calendar-config');
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
    return el.querySelector('[data-serixa-calendar-live]');
  }

  function announce(el, text) {
    var live = liveRegion(el);
    if (!live) {
      return;
    }
    live.textContent = text || '';
  }

  function daysOf(el) {
    return Array.prototype.slice.call(el.querySelectorAll('[data-serixa-calendar-day]'));
  }

  function clearSelection(el) {
    el.querySelectorAll('[aria-selected="true"]').forEach(function (node) {
      node.setAttribute('aria-selected', 'false');
      node.classList.remove('calendar-day-selected');
    });
    el.querySelectorAll('.calendar-drag-highlight').forEach(function (node) {
      node.classList.remove('calendar-drag-highlight');
    });
  }

  function selectDay(el, cell) {
    clearSelection(el);
    cell.setAttribute('aria-selected', 'true');
    cell.classList.add('calendar-day-selected');
    var iso = cell.getAttribute('data-serixa-calendar-day');
    announce(el, cell.getAttribute('aria-label') || iso || 'Selected');

    var input = el.querySelector('[data-serixa-calendar-input="date"], [data-serixa-calendar-input="datetime"], [data-serixa-calendar-input="start"]');
    if (input && iso) {
      if (input.getAttribute('data-serixa-calendar-input') === 'datetime') {
        var time = (input.value && input.value.indexOf('T') > -1) ? input.value.split('T')[1] : '09:00';
        input.value = iso + 'T' + time;
      } else {
        input.value = iso;
      }
      input.dispatchEvent(new Event('change', { bubbles: true }));
    }

    var selectBtn = cell.querySelector('[data-serixa-calendar-select]');
    if (selectBtn) {
      selectBtn.focus();
    } else {
      cell.focus();
    }
  }

  function bindGrid(el) {
    var cells = daysOf(el);
    var index = cells.findIndex(function (c) {
      return c.getAttribute('aria-selected') === 'true' || c.getAttribute('aria-current') === 'date';
    });

    cells.forEach(function (cell) {
      if (!cell.hasAttribute('tabindex')) {
        cell.setAttribute('tabindex', '-1');
      }

      cell.addEventListener('click', function () {
        selectDay(el, cell);
        index = cells.indexOf(cell);
      });

      cell.addEventListener('focus', function () {
        announce(el, cell.getAttribute('aria-label') || '');
      });
    });

    el.addEventListener('keydown', function (e) {
      if (cells.length === 0) {
        return;
      }
      var cols = 7;
      var next = index < 0 ? 0 : index;

      if (e.key === 'ArrowRight') {
        next = (index + 1) % cells.length;
      } else if (e.key === 'ArrowLeft') {
        next = (index - 1 + cells.length) % cells.length;
      } else if (e.key === 'ArrowDown') {
        next = Math.min(cells.length - 1, (index < 0 ? 0 : index) + cols);
      } else if (e.key === 'ArrowUp') {
        next = Math.max(0, (index < 0 ? 0 : index) - cols);
      } else if (e.key === 'Enter' || e.key === ' ') {
        if (index >= 0) {
          e.preventDefault();
          selectDay(el, cells[index]);
        }
        return;
      } else {
        return;
      }

      e.preventDefault();
      index = next;
      cells[index].setAttribute('tabindex', '0');
      cells[index].focus();
    });

    // Drag highlight for range pickers
    var dragging = false;
    var dragStart = null;

    el.addEventListener('mousedown', function (e) {
      var cell = e.target.closest('[data-serixa-calendar-day]');
      if (!cell || el.getAttribute('data-serixa-calendar-kind') !== 'date-range-picker') {
        return;
      }
      dragging = true;
      dragStart = cell.getAttribute('data-serixa-calendar-day');
      clearSelection(el);
      cell.classList.add('calendar-drag-highlight');
    });

    el.addEventListener('mouseover', function (e) {
      if (!dragging) {
        return;
      }
      var cell = e.target.closest('[data-serixa-calendar-day]');
      if (!cell) {
        return;
      }
      cell.classList.add('calendar-drag-highlight');
    });

    el.addEventListener('mouseup', function (e) {
      if (!dragging) {
        return;
      }
      dragging = false;
      var cell = e.target.closest('[data-serixa-calendar-day]');
      var endIso = cell ? cell.getAttribute('data-serixa-calendar-day') : null;
      var startInput = el.querySelector('[data-serixa-calendar-input="start"]');
      var endInput = el.querySelector('[data-serixa-calendar-input="end"]');
      if (startInput && dragStart) {
        startInput.value = dragStart;
        startInput.dispatchEvent(new Event('change', { bubbles: true }));
      }
      if (endInput && endIso) {
        endInput.value = endIso;
        endInput.dispatchEvent(new Event('change', { bubbles: true }));
      }
      announce(el, 'Range ' + (dragStart || '') + ' to ' + (endIso || ''));
      dragStart = null;
    });
  }

  function bindToolbar(el) {
    el.querySelectorAll('[data-serixa-calendar-nav]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var action = btn.getAttribute('data-serixa-calendar-nav');
        announce(el, 'Navigate ' + (action || ''));
        el.dispatchEvent(new CustomEvent('serixa:calendar-nav', {
          bubbles: true,
          detail: { action: action, config: parseConfig(el) }
        }));
      });
    });

    el.querySelectorAll('[data-serixa-calendar-view]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var view = btn.getAttribute('data-serixa-calendar-view');
        el.querySelectorAll('[data-serixa-calendar-view]').forEach(function (b) {
          b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
        });
        announce(el, 'View ' + (view || ''));
        el.dispatchEvent(new CustomEvent('serixa:calendar-view', {
          bubbles: true,
          detail: { view: view, config: parseConfig(el) }
        }));
      });
    });
  }

  function bindEvents(el) {
    el.querySelectorAll('[data-serixa-calendar-event]').forEach(function (node) {
      if (!node.hasAttribute('tabindex')) {
        node.setAttribute('tabindex', '0');
      }

      function activate() {
        var label = node.getAttribute('aria-label') || node.textContent || 'Event';
        announce(el, label.trim());
        var id = node.getAttribute('data-event-id');
        var pop = id ? el.querySelector('[data-serixa-calendar-popover][data-event-id="' + id + '"]') : null;
        if (pop) {
          pop.hidden = false;
          pop.removeAttribute('hidden');
        }
      }

      node.addEventListener('focus', activate);
      node.addEventListener('click', activate);
    });
  }

  function bindCalendar(el) {
    if (el.getAttribute('data-serixa-module-bound') === '1') {
      return;
    }
    el.setAttribute('data-serixa-module-bound', '1');
    bindGrid(el);
    bindToolbar(el);
    bindEvents(el);
  }

  Serixa.module('calendar', {
    mount: function (root) {
      root.querySelectorAll('[data-serixa-calendar]').forEach(bindCalendar);
    }
  });
})(typeof window !== 'undefined' ? window : this);
