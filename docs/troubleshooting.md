# Troubleshooting

Common problems, why they happen, and how to fix them.

## Runtime script 404 {#runtime-script-404}

**Symptom:** interactive widgets (`Modal`, `Dropdown`, `Tabs`, `Toast`, …) render fine in the HTML but clicking them does nothing. Your browser's network tab shows `assets/runtime/serixa.js` returning 404.

**Cause:** `ClientRuntime::register($assets, $url)` only writes a `<script defer src="$url">` tag. It does not guarantee that file exists at that URL — serving it is your app's job.

**Fix:**

1. Confirm you're serving from `public/`, not the app root: `php -S localhost:8000 -t public`. If you serve from the app root, `/assets/runtime/serixa.js` resolves to a path that doesn't exist.
2. Confirm the file is actually there: `serixa new` copies it to `public/assets/runtime/serixa.js` automatically. If you deleted it, copy it back from `Serixa\Runtime\ClientRuntime::scriptPath()`.
3. Confirm the URL you passed to `register()` matches where the file lives:

   ```php
   ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');
   ```

   If you moved the file to a different path or CDN, update this URL to match.

## Progressive enhancement attributes doing nothing (PE no-ops) {#pe-no-ops}

**Symptom:** you called `->onClick('someHandler')` or similar, the `data-serixa-on-click="someHandler"` attribute appears in the rendered HTML, but nothing happens when clicked.

**Cause:** fluent DOM event methods (`onClick`, `onChange`, `onInput`, `onSubmit`, `onFocus`, `onBlur`, `onKeyDown`, `onKeyUp`) only emit `data-serixa-on-*` **attributes**. They do not register a JavaScript function for you. Something still has to call `Serixa.on('someHandler', function (event) { ... })` in your own script.

**Fix:**

1. Make sure the runtime script is actually loaded (see [above](#runtime-script-404)).
2. Register the handler yourself, after the runtime script loads:

   ```html
   <script>
     Serixa.on('someHandler', function (event) {
       // your logic
     });
   </script>
   ```

3. For built-in interactive widgets (`Modal`, `Dropdown`, `Tabs`, `Accordion`, `Toast`), you don't need `onClick` at all — use the widget's own `data-serixa-*` wiring (`data-serixa-open-modal`, `data-serixa-show-toast`, …) as shown in [interactive-components.md](interactive-components.md). Those are bound automatically by the runtime's built-in modules; custom `onClick` handlers are for your own app logic, not for opening framework widgets.

## Composer path-repository recursion in a monorepo {#path-repository-recursion}

**Symptom:** `composer install` hangs, or disk/IDE tooling (search, file watchers, backups) becomes extremely slow or appears to hang, when a SERIXA app lives **inside** a clone of the framework itself (e.g. `framework/my-app/`).

**Cause:** apps generated with a `path` repository pointing at the framework use a symlinked `vendor/serixa/framework`. If the app directory is *also* inside the framework tree, that symlink points back into a parent directory, and any tool that recursively walks the filesystem (naive LOC counters, some IDE indexers, `find`/`grep -r`) can loop through `vendor/serixa/framework/<app>/vendor/serixa/framework/...` indefinitely.

**Fix:**

- Generate new apps in a **sibling** directory, outside the framework clone:

  ```bash
  cd ..
  serixa new blog
  ```

  `serixa new` warns you about this automatically if you run it from inside the framework tree.
- If you must keep a sample app inside the framework repo (as SERIXA's own `validation/` and `playground/` apps do), exclude `vendor/` from recursive scans and restrict any LOC/search tooling to `pages/`, `components/`, `layouts/`, `public/`, `config/`.
- Before deploying, switch `composer.json` away from the `path` repository to a real version constraint (see [deployment.md](deployment.md#composer-path-repositories-in-production)) — this removes the symlink entirely.

## "Unknown theme component" / invalid theme state exceptions {#unknown-theme-state}

**Symptom:** an `InvalidArgumentException` like:

```text
Unknown theme component "buton".
```

or

```text
Invalid button theme state: variant="huge" size="md". Allowed variants: primary, secondary, danger, ghost. Allowed sizes: sm, md, lg.
```

**Cause:** `DefaultTheme::classes($component, $state)` only knows a fixed set of component keys and, for many widgets, a fixed set of allowed prop values (checked via `assertOption()` on the widget or validated inside the theme). This is intentional — a typo or unsupported variant fails loudly at render time instead of silently rendering with no classes.

**Fix:**

- Read the exception message; it lists the allowed values directly (e.g. "Allowed variants: primary, secondary, danger, ghost").
- If you're implementing a **custom theme** (`serixa make:theme`), make sure it handles every component key your app's widgets use — `ThemeInterface::classes()` is expected to either return classes or throw, not return an empty array silently.
- This is different from a validation error on user input — it only fires for programmer-facing widget configuration (e.g. `->color('huge')`), not for data your users type into a form.

## Duplicate `make:*` generation refused {#duplicate-make-commands}

**Symptom:**

```text
Error: Refusing to overwrite existing file: /path/to/pages/ArticlesPage.php
Duplicate generation blocked — rename the target or delete the existing file first.
```

**Cause:** `make:page`, `make:component`, `make:layout`, `make:theme`, and `make:plugin` never overwrite an existing file. This protects hand-written edits from being silently discarded by a repeated command.

**Fix:** rename the class you're generating, or delete/rename the existing file first, then re-run the command.

## Composer path repository points at the wrong place {#composer-path-repo}

**Symptom:** `composer install` fails with something like "could not find package serixa/framework" or resolves an unexpected version, right after `serixa new`.

**Cause:** the generated `composer.json` has a `path` repository whose `url` is the framework directory detected at generation time:

```json
"repositories": [
    {
        "type": "path",
        "url": "D:/yasas/PersonalProjects/Serixa",
        "options": { "symlink": true }
    }
]
```

If you move the app to a different machine, or the framework clone moves/is deleted, this path is stale.

**Fix:**

- On a new machine, edit the `url` to point at wherever you cloned SERIXA locally, or switch to a version constraint if you don't need local development against the framework source:

  ```json
  "require": {
      "serixa/framework": "^0.11"
  }
  ```

  (remove the `repositories` block once you do this).
- Re-run `composer install` (or `composer update`) after changing the path.
- Keep app directories **outside** the framework clone to avoid the recursion issue above.

## Still stuck?

- Check [faq.md](faq.md) for scope questions ("does SERIXA have X?").
- Re-read [getting-started.md](getting-started.md) for the core mental model (widgets → theme → renderer → document).
- Compare your code against the working examples in `examples/*.php` or the `validation/` sample apps in the framework repo.
