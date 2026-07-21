# SERIXA Icons

Official icon system for the SERIXA ecosystem (`serixa/icons`).

Inline SVG · SSR · no JavaScript · no CDN · no icon fonts.

## Install (monorepo)

```bash
cd packages/icons
composer install
```

Path repository points at `../../framework`.

## Quick start

```php
use Serixa\Icons\Component\Icon;
use Serixa\Rendering\Renderer;

echo (new Renderer())->render(Icon::make('search')->size('lg')->color('primary'));
```

## API

| Method | Purpose |
|--------|---------|
| `Icon::make('name')` | Create icon (optional `pack:name`) |
| `->pack('feather')` | Select pack |
| `->size('sm'|'md'|…)` | Theme size |
| `->color('primary'|…)` | Semantic color |
| `->class(...)` | Extra CSS classes (Widget) |
| `->ariaLabel('…')` | Informative icon |
| `->decorative()` | `aria-hidden` |
| `->title('…')` | SVG `<title>` |
| `->strokeWidth(1.5)` | Outline stroke |
| `->filled()` / `->outlined()` | Paint mode |

Default pack: **Heroicons**. Also registered: **Feather**, **Lucide**.

## Docs

See [docs/icons.md](docs/icons.md).

## Quality

```bash
composer qa
```

## License

MIT
