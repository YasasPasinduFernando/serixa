/**
 * SERIXA DevTools client runtime.
 * Registers with Serixa.module when available; self-boots standalone otherwise.
 */
(function (global) {
  'use strict';

  var HIGHLIGHT_CLASS = 'serixa-devtools-highlight';

  function mount(root) {
    if (!root || root.getAttribute('data-serixa-devtools-mounted') === '1') {
      return;
    }

    root.setAttribute('data-serixa-devtools-mounted', '1');

    var payload = readPayload(root);
    var live = ensureLiveRegion(root);
    var inspectorPane = ensureInspectorPane(root);
    var highlighted = null;
    var inspectorActive = false;

    root.querySelectorAll('[data-devtools-tab]').forEach(function (tab) {
      tab.addEventListener('click', function () {
        switchTab(root, tab.getAttribute('data-devtools-tab'));
        announce(live, 'Switched to ' + tab.textContent + ' panel');
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.ctrlKey && event.shiftKey && (event.key === 'D' || event.key === 'd')) {
        event.preventDefault();
        root.hidden = !root.hidden;
        announce(live, root.hidden ? 'DevTools hidden' : 'DevTools visible');
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Alt') {
        inspectorActive = event.type === 'keydown';
        if (!inspectorActive) {
          clearHighlight(highlighted);
          highlighted = null;
        }
      }
    });

    document.addEventListener('keyup', function (event) {
      if (event.key === 'Alt') {
        inspectorActive = false;
        clearHighlight(highlighted);
        highlighted = null;
      }
    });

    document.addEventListener('mousemove', function (event) {
      if (!inspectorActive || root.hidden) {
        return;
      }

      var target = document.elementFromPoint(event.clientX, event.clientY);
      if (!target || root.contains(target)) {
        clearHighlight(highlighted);
        highlighted = null;
        return;
      }

      if (target === highlighted) {
        return;
      }

      clearHighlight(highlighted);
      highlighted = target;
      highlighted.classList.add(HIGHLIGHT_CLASS);
    });

    document.addEventListener('click', function (event) {
      if (!inspectorActive || root.hidden) {
        return;
      }

      var target = event.target;
      if (!(target instanceof Element) || root.contains(target)) {
        return;
      }

      event.preventDefault();
      event.stopPropagation();

      var summary = describeElement(target);
      inspectorPane.textContent = summary;
      announce(live, summary);
    });

    if (payload && payload.package) {
      announce(live, 'DevTools ready');
    }
  }

  function readPayload(root) {
    var node = root.querySelector('#serixa-devtools-data')
      || document.getElementById('serixa-devtools-data');

    if (!node || !node.textContent) {
      return null;
    }

    try {
      return JSON.parse(node.textContent);
    } catch (error) {
      return null;
    }
  }

  function ensureLiveRegion(root) {
    var existing = root.querySelector('[data-devtools-live]');
    if (existing) {
      return existing;
    }

    var live = document.createElement('div');
    live.setAttribute('data-devtools-live', '');
    live.setAttribute('aria-live', 'polite');
    live.setAttribute('aria-atomic', 'true');
    root.appendChild(live);

    return live;
  }

  function ensureInspectorPane(root) {
    var existing = root.querySelector('[data-devtools-inspector-pane]');
    if (existing) {
      return existing;
    }

    var pane = document.createElement('div');
    pane.setAttribute('data-devtools-inspector-pane', '');
    pane.setAttribute('aria-label', 'Element inspector');
    pane.textContent = 'Hold Alt and hover to highlight. Click to inspect.';
    root.appendChild(pane);

    return pane;
  }

  function switchTab(root, name) {
    root.querySelectorAll('[data-devtools-tab]').forEach(function (tab) {
      var active = tab.getAttribute('data-devtools-tab') === name;
      tab.setAttribute('aria-selected', active ? 'true' : 'false');
    });

    root.querySelectorAll('[data-devtools-panel-content]').forEach(function (panel) {
      panel.hidden = panel.getAttribute('data-devtools-panel-content') !== name;
    });
  }

  function clearHighlight(element) {
    if (element) {
      element.classList.remove(HIGHLIGHT_CLASS);
    }
  }

  function describeElement(element) {
    var parts = [element.tagName.toLowerCase()];

    if (element.id) {
      parts.push('#' + element.id);
    }

    if (element.classList && element.classList.length > 0) {
      parts.push('.' + Array.prototype.join.call(element.classList, '.'));
    }

    return parts.join('');
  }

  function announce(live, message) {
    if (!live || !message) {
      return;
    }

    live.textContent = message;
  }

  function boot() {
    var root = document.getElementById('serixa-devtools');
    if (!root) {
      return;
    }

    mount(root);
  }

  if (global.Serixa && typeof global.Serixa.module === 'function') {
    global.Serixa.module('devtools', {
      mount: function (root) {
        mount(root || document.getElementById('serixa-devtools'));
      },
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
  } else {
    boot();
  }
})(typeof window !== 'undefined' ? window : globalThis);
