# SERIXA Icons

The official icon system for SERIXA applications.

## Why a package?

Icons are optional and large. Shipping them as `serixa/icons` keeps `serixa/framework` lean and lets the icon catalog version independently.

## API

```php
use Serixa\Icons\Component\Icon;

Icon::make('search');
Icon::make('home')->decorative();
Icon::make('mail')->ariaLabel('Email');
Icon::make('star')->size('lg')->color('warning')->filled();
Icon::make('search')->pack('feather');
Icon::make('lucide:house');
```

Sizes: `xs`, `sm`, `md`, `lg`, `xl`, `2xl`  
Colors: `primary`, `secondary`, `success`, `danger`, `warning`, `muted`, `inherit`

## Accessibility

| Mode | Behavior |
|------|----------|
| Decorative (default when no label/title) | `aria-hidden="true"`, `focusable="false"` |
| Informative (`ariaLabel` / `title` / `decorative(false)`) | `role="img"`, `aria-label`, optional `<title>` |

Never rely on color alone for meaning — pair status icons with text when needed.

## Theme integration

`Icon` calls `ThemeInterface::classes('icon', …)`. `DefaultTheme` maps sizes and semantic colors to utility classes. SVG strokes/fills use `currentColor`, so text color classes paint the glyph.

No hardcoded Tailwind inside the icon package render path (beyond what the active theme returns).

## Custom icon packs

```php
use Serixa\Icons\Contracts\IconPackInterface;
use Serixa\Icons\Registry\IconRegistry;

final class BrandIcons implements IconPackInterface
{
    public function name(): string { return 'brand'; }
    // … icons(), has(), viewBox(), paths(), defaultStrokeWidth()
}

IconRegistry::default()->register(new BrandIcons());
IconRegistry::default()->defaultPack('brand');
```

Plugins can register packs at bootstrap without modifying framework code.

## Performance

- Paths are PHP arrays (no disk I/O per icon).
- Theme class lists are memoized by `DefaultTheme` (framework v0.11+).
- Inline SVG avoids extra HTTP requests; catalog size grows with icons used in the tree only (unused icons are never rendered).

## Packs

| Pack | Name | Notes |
|------|------|-------|
| Heroicons | `heroicons` | Default; friendly aliases (`menu`, `search`, …) |
| Feather | `feather` | Feather-oriented aliases; stroke 2 |
| Lucide | `lucide` | Lucide-oriented aliases; stroke 2 |

Shared geometry lives in `OutlineCatalog` for a consistent 24×24 outline set.
