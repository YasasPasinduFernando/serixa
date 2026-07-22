# Asset pipeline

See also [styling.md](styling.md) for the Theme Compiler and `.sx-*` recipes.

SERIXA ships an **offline CSS build**. Apps never load `cdn.tailwindcss.com` or a runtime Tailwind compiler.

## Layout

```text
framework/assets/css/serixa.css   # utility foundation (DefaultTheme maps)
resources/css/app.css             # app overrides
public/assets/serixa.css          # compiled output (theme + foundation + app)
public/assets/runtime/serixa.js   # progressive-enhancement runtime
public/assets/manifest.json       # asset map from serixa build
build/serixa.css
build/theme-manifest.json
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

1. Compiles theme tokens + `.sx-*` recipes via `ThemeCompiler`
2. Appends utility foundation + `resources/css/app.css` → `public/assets/serixa.css`
3. Copies `ClientRuntime` → `public/assets/runtime/serixa.js`
4. Writes manifests and a build report

## Registering assets

```php
use Serixa\Styling\StyleSheet;
use Serixa\Runtime\ClientRuntime;

StyleSheet::register($document->assets()); // /assets/serixa.css
ClientRuntime::register($document->assets());
```

`Serixa\Assets\StyleSheet` still works as a compatibility alias.

## Rebuilding the utility foundation

Maintainers regenerating `framework/assets/css/serixa.css` (requires Node once):

```bash
cd tools/css
npm install
npx tailwindcss -i ./input.css -o ../../framework/assets/css/serixa.css --minify
```

Apps and CI stay offline — they only consume the committed foundation plus the PHP Theme Compiler output.
