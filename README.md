# SERIXA

**SERIXA** is a Flutter-inspired PHP UI framework: write PHP widgets, get clean semantic HTML.

> Write PHP. SERIXA renders HTML, applies Tailwind through a theme, handles responsive layout, and cares about accessibility.

PHP namespace: **`Serixa\`**. See [docs/NAMESPACE.md](docs/NAMESPACE.md).

## Vision

Frontend development should feel like Flutter — developers focus on business logic, not HTML, Tailwind classes, or JavaScript boilerplate for common UI.

SERIXA is not a Laravel clone and not a React clone. It is a **components-first PHP UI engine** with a long-lived Runtime Core.

## Features

- Fluent, chainable widgets (`Button::make('Save')->primary()->large()`)
- Layout widgets: `Row`, `Column`, `Center`, `Padding`, `Spacer`, `Expanded`, `SizedBox`, `Stack`, `Divider`, `Scaffold`
- **UI Foundation:** forms, Scaffold chrome, Link, …
- **Data Display:** `DataTable` (headers/rows + composition), `StatCard`, `Metric`, `Pagination`, `Avatar`, `Toolbar`, …
- Fluent `Padding::make()->x(6)->y(4)->child(...)`
- Theme seam + design tokens
- Widget tree + lifecycle hooks
- **Runtime Core:** WidgetContext, RenderContext, Container, Registry, Render Pipeline, Plugins
- **Document & Asset Engine:** full HTML pages, SEO, CSS/JS asset registry
- Memory-only state manager
- Accessibility helpers
- Escaped output by default
- PSR-4 (`Serixa\`), PHP 8.2+, PHPUnit-tested

## Status

| Milestone | Name | Status |
|-----------|------|--------|
| 1 | Rendering engine | Complete |
| 2 | Layout engine + theme | Complete |
| 3 | Framework foundations | Complete (`v0.3.0`) |
| 4 | Runtime Core | Complete (`v0.4.0`) |
| 5 | Document & Asset Engine | Complete (`v0.5.0`) |
| 5.1 | API Stewardship | Complete (`v0.5.1`) |
| 5.2 | DX cleanup | Complete (`v0.5.2`) |
| 6.0 | UI Foundation | Complete (`v0.6.0`) |
| 7.0 | Data Display & Application Widgets | Complete (`v0.7.0`) |

See [ROADMAP.md](ROADMAP.md) and [CHANGELOG.md](CHANGELOG.md).

## Requirements

- PHP 8.2+
- Composer

## Installation

```bash
composer require serixa/framework
```

For local development:

```bash
composer install
composer test
```

## Quick start

```php
use Serixa\Component\Button;
use Serixa\Component\Center;
use Serixa\Component\Column;
use Serixa\Component\SizedBox;
use Serixa\Component\Text;
use Serixa\Rendering\Renderer;

$html = (new Renderer())->render(
    Center::make(
        Column::make([
            Text::make('Welcome'),
            SizedBox::height(20),
            Button::make('Continue')->primary()->large(),
        ]),
    ),
);
```

## Runtime context example

```php
use Serixa\Rendering\Renderer;
use Serixa\Runtime\WidgetContext;

$renderer = new Renderer(
    null,
    WidgetContext::defaults()->withColorMode('dark')->withLocale('en'),
);
```

## Document example

```php
use Serixa\Document\Document;
use Serixa\Component\Button;
use Serixa\Component\Text;
use Serixa\Rendering\Renderer;

$html = (new Renderer())->render(
    Document::make()
        ->title('Dashboard')
        ->description('SERIXA Admin')
        ->body(
            Text::make('Hello'),
            Button::make('Continue')->primary(),
        ),
);
```

## Examples

```bash
php examples/basic.php
php examples/foundations.php
php examples/runtime.php
php examples/document.php
```

## Project structure

```text
src/
  Accessibility/   ARIA helpers
  Component/       Concrete widgets (+ Component BC alias)
  Container/       Service container
  Contracts/       Interfaces
  Document/        Full HTML documents, SEO, assets
  Event/           Event contracts
  Inspector/       DevTools contracts + snapshots
  Performance/     Profiler contracts
  Pipeline/        Render pipeline + passes
  Plugin/          Plugin contracts + manager
  Registry/        Widget registry + metadata
  Rendering/       Renderer, HtmlWriter
  Runtime/         WidgetContext, RenderContext
  State/           Memory-only state engine
  Support/         Escaper, attributes, class merge
  Theme/           DefaultTheme, ThemeManager
  Token/           Design tokens
  Tree/            WidgetTree, TreeNode, TreeWalker
  Widget/          Widget base, factory, collection
tests/
docs/
examples/
```

## Documentation

- [Architecture overview](docs/architecture.md)
- [Namespace policy](docs/NAMESPACE.md)
- [API stability](docs/API_STABILITY.md)
- [Versioning](docs/VERSIONING.md)
- [Deprecation policy](docs/DEPRECATION.md)
- [Coding standards](docs/CODING_STANDARDS.md)
- [ADRs](docs/adr/README.md)
- [Rendering engine](docs/rendering-engine.md)
- [Layout engine](docs/layout-engine.md)
- [Framework foundations](docs/framework-foundations.md)
- [Runtime Core](docs/runtime-core.md)
- [Document & Asset Engine](docs/document-engine.md)
- [UI Foundation](docs/ui-foundation.md)
- [Data Display](docs/data-display.md)

## Roadmap

See [ROADMAP.md](ROADMAP.md).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) and [docs/CODING_STANDARDS.md](docs/CODING_STANDARDS.md).

Quality gate:

```bash
composer qa
```

## License

MIT — see [LICENSE](LICENSE).
