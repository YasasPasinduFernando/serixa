# SERIXA styling engine

SERIXA compiles design tokens into static CSS. Apps never load `cdn.tailwindcss.com` or a runtime Tailwind compiler.

## Architecture

```text
Widgets
  → Theme tokens / DefaultTheme class maps
  → SERIXA Theme Compiler (Serixa\Styling)
  → Optimized CSS
  → public/assets/serixa.css
```

### Components (`framework/src/Styling/`)

| Class | Role |
|-------|------|
| `StyleProperty` | One CSS declaration |
| `StyleSelector` | Rule selector |
| `StyleRule` | Selector + properties |
| `ThemeCompiler` | Tokens → CSS variables + `.sx-*` recipes |
| `CssOptimizer` | Deduplicate selectors / merge properties |
| `CssWriter` | Serialize and write CSS |
| `StyleCompiler` | Full project pipeline |
| `StyleSheet` | Register `/assets/serixa.css` + `build()` |
| `ThemeManifest` | Build metadata (selectors, tokens, sizes) |

`Serixa\Assets\StyleSheet` remains as a thin compatibility façade.

## Theme compilation

`ThemeCompiler` emits:

1. **`:root` / `.dark` CSS variables** from design tokens  
   Example: `--sx-color-primary: #0284c7`
2. **Semantic component classes**  
   Example: `button.primary` → `.sx-btn-primary { background: var(--sx-color-primary); … }`

Duplicate selectors are merged; later properties win.

## Build pipeline

```bash
vendor/bin/serixa build
# website:
composer build   # → php bin/build-assets.php
```

Steps:

1. Compile theme CSS (tokens + `.sx-*` recipes)
2. Append committed utility foundation (`framework/assets/css/serixa.css`) for DefaultTheme maps
3. Append app `resources/css/app.css`
4. Optimize / dedupe chunks
5. Write `public/assets/serixa.css` (+ BC alias `public/assets/app.css`)
6. Copy `serixa.js` runtime
7. Write `manifest.json` + `theme-manifest.json`
8. Print build report

## Registering assets

```php
use Serixa\Styling\StyleSheet;
use Serixa\Runtime\ClientRuntime;

StyleSheet::register($document->assets()); // /assets/serixa.css
ClientRuntime::register($document->assets());
```

Widget APIs (`Button::make()->primary()`, layout `fill()`, etc.) are unchanged. Only the styling backend compiles offline.

## Theme tokens

Token classes live under `Serixa\Token\` (`ColorToken`, `SpacingToken`, `RadiusToken`, …).  
`DefaultTheme` maps tokens to utility classes for widgets; `ThemeCompiler` maps the same semantics to CSS variables and `.sx-*` recipes.

## Offline

No internet is required at build or runtime. Rebuild the optional utility foundation with Node only when maintainers change safelists (`tools/css/`); apps consume the committed files.
