/**
 * SERIXA client runtime — progressive enhancement foundation.
 *
 * NO virtual DOM, hydration, routing, or state engine.
 * Discovers data-serixa-on-* attributes and optionally invokes registered handlers.
 * Native browser behavior is preserved when no handler is registered.
 */
(function (global) {
  'use strict';

  var ATTR_PREFIX = 'data-serixa-on-';
  var EVENT_MAP = {
    click: 'click',
    change: 'change',
    input: 'input',
    submit: 'submit',
    focus: 'focus',
    blur: 'blur',
    keydown: 'keydown',
    keyup: 'keyup'
  };

  /** @type {Object.<string, Function>} */
  var handlers = Object.create(null);

  /** @type {Object.<string, Array<Function>>} */
  var plugins = Object.create(null);

  function register(name, fn) {
    if (typeof name !== 'string' || name === '') {
      return Serixa;
    }
    if (typeof fn === 'function') {
      handlers[name] = fn;
    }
    return Serixa;
  }

  function unregister(name) {
    delete handlers[name];
    return Serixa;
  }

  function get(name) {
    return handlers[name] || null;
  }

  /**
   * Future modules plug in here (e.g. focus trap, modal).
   */
  function use(hook, fn) {
    if (typeof hook !== 'string' || typeof fn !== 'function') {
      return Serixa;
    }
    if (!plugins[hook]) {
      plugins[hook] = [];
    }
    plugins[hook].push(fn);
    return Serixa;
  }

  function runPlugins(hook, payload) {
    var list = plugins[hook] || [];
    for (var i = 0; i < list.length; i++) {
      try {
        list[i](payload);
      } catch (err) {
        if (global.console && console.error) {
          console.error('[serixa] plugin error', hook, err);
        }
      }
    }
  }

  function lookupWidgets(root) {
    var nodes = root.querySelectorAll('[data-serixa-on-click],[data-serixa-on-change],[data-serixa-on-input],[data-serixa-on-submit],[data-serixa-on-focus],[data-serixa-on-blur],[data-serixa-on-keydown],[data-serixa-on-keyup]');
    return Array.prototype.slice.call(nodes);
  }

  function bindElement(el) {
    if (el.getAttribute('data-serixa-bound') === '1') {
      return;
    }
    el.setAttribute('data-serixa-bound', '1');

    Object.keys(EVENT_MAP).forEach(function (key) {
      var attr = ATTR_PREFIX + key;
      if (!el.hasAttribute(attr)) {
        return;
      }
      var handlerName = el.getAttribute(attr);
      var domEvent = EVENT_MAP[key];

      el.addEventListener(domEvent, function (event) {
        var fn = handlers[handlerName];
        var detail = {
          name: handlerName,
          event: domEvent,
          element: el,
          originalEvent: event
        };

        runPlugins('before:' + domEvent, detail);

        if (typeof fn === 'function') {
          fn(event, el, detail);
        } else {
          // Metadata path: announce for tooling / future plugins; do not cancel native behavior.
          var custom = new CustomEvent('serixa:' + handlerName, {
            bubbles: true,
            cancelable: true,
            detail: detail
          });
          el.dispatchEvent(custom);
        }

        runPlugins('after:' + domEvent, detail);
      });
    });
  }

  function boot(root) {
    root = root || global.document;
    if (!root || !root.querySelectorAll) {
      return Serixa;
    }
    lookupWidgets(root).forEach(bindElement);
    runPlugins('boot', { root: root });
    return Serixa;
  }

  var Serixa = {
    version: '0.8.0',
    register: register,
    unregister: unregister,
    get: get,
    use: use,
    boot: boot,
    lookup: lookupWidgets
  };

  global.Serixa = Serixa;

  if (global.document) {
    if (global.document.readyState === 'loading') {
      global.document.addEventListener('DOMContentLoaded', function () {
        boot(global.document);
      });
    } else {
      boot(global.document);
    }
  }
})(typeof window !== 'undefined' ? window : globalThis);
