# Asset pipeline

SERIXA ships an **offline CSS build**. Apps never load `cdn.tailwindcss.com` or a runtime Tailwind compiler.

## Layout

```text
framework/assets/css/serixa.css   # official base (theme utilities, committed)
resources/css/app.css             # app overrides
public/assets/app.css             # compiled output (base + app)
public/assets/runtime/serixa.js   # progressive-enhancement runtime
public/assets/manifest.json       # asset map from serixa build
build/manifest.json
build/app.css
build/tokens.json
build/report.json
```

## Build

```bash
vendor/bin/serixa build
# or, in the docs website:
composer build   # → php bin/build-assets.php
```

`serixa build`:

1. Writes theme token stub → `build/tokens.json`
2. Merges `serixa.css` + `resources/css/app.css` → `public/assets/app.css`
3. Copies `ClientRuntime` → `public/assets/runtime/serixa.js`
4. Writes `build/manifest.json` and `public/assets/manifest.json`
5. Prints a build report (sizes, sources, elapsed ms)

## Registering assets

```php
use Serixa\Assets\StyleSheet;
use Serixa\Runtime\ClientRuntime;

StyleSheet::register($document->assets()); // /assets/app.css
ClientRuntime::register($document->assets());
```

## Rebuilding the base stylesheet

Maintainers regenerating `framework/assets/css/serixa.css` (requires Node once):

```bash
cd tools/css
npm install
npx tailwindcss -i ./input.css -o ../../framework/assets/css/serixa.css --minify
```

Apps and CI stay offline — they only consume the committed `serixa.css`.
