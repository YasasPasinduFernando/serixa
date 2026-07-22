# Migration guide — SERIXA v1.0

Public widget APIs are unchanged. Styling delivery changed.

## From Tailwind Play CDN

1. Remove any `cdn.tailwindcss.com` script tags.
2. Require `serixa/cli` (or use the website `composer build` script).
3. Run `vendor/bin/serixa build`.
4. Register CSS:

```php
use Serixa\Styling\StyleSheet;
use Serixa\Runtime\ClientRuntime;

StyleSheet::register($document->assets()); // /assets/serixa.css
ClientRuntime::register($document->assets());
```

`Serixa\Assets\StyleSheet` still works as a deprecated alias.

## From `/assets/app.css`

Prefer `/assets/serixa.css`. The build still writes a BC copy of `public/assets/app.css` with the same contents.

## Layout width

Generic `Column` / `Row` / `Center` / `Stack` no longer force `w-full`. Use `fill()` when needed; use `Expanded` for fluid panes beside rails.

## Buttons

Primary/secondary/danger/ghost variants emit semantic `.sx-btn-*` classes (plus layout utilities). Ensure compiled CSS is present.
