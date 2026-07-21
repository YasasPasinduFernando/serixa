# SERIXA

**SERIXA** is a Flutter-inspired PHP UI framework: write PHP widgets, get clean semantic HTML.

> Write PHP. SERIXA renders HTML, applies Tailwind through a theme, handles responsive layout, and cares about accessibility.

PHP namespace: **`Serixa\`**. See [docs/NAMESPACE.md](docs/NAMESPACE.md).

## Vision

Frontend development should feel like Flutter — developers focus on business logic, not HTML, Tailwind classes, or JavaScript boilerplate for common UI.

SERIXA is not a Laravel clone and not a React clone. It is a **components-first PHP UI engine** with a long-lived Runtime Core.

## Beginner path

New to SERIXA? Start here instead of the reference docs below:

1. [Getting started](docs/getting-started.md) — what SERIXA is (and isn't), core concepts, minimal example.
2. [Build a blog in 30 minutes](docs/first-blog.md) — full copy-paste tutorial: CLI, pages, layout, a card list, a contact form.
3. [Build an admin dashboard shell](docs/first-dashboard.md) — sidebar, stat cards, a data table sketch.

```bash
serixa new blog
cd blog
composer install
php -S localhost:8000 -t public
```

Then open http://localhost:8000. See [docs/troubleshooting.md](docs/troubleshooting.md) if something doesn't work.

## Features

- Fluent, chainable widgets (`Button::make('Save')->primary()->large()`)
- Layout widgets: `Row`, `Column`, `Center`, `Padding`, `Spacer`, `Expanded`, `SizedBox`, `Stack`, `Divider`, `Scaffold`
- **UI Foundation:** forms, Scaffold chrome, Link, …
- **Data Display:** `DataTable` (headers/rows + composition), `StatCard`, `Metric`, `Pagination`, `Avatar`, `Toolbar`, …
- **Progressive Enhancement:** `onClick` / `onSubmit` / …, HTML constraints, tiny `serixa.js` runtime (no VDOM)
- **Interactive Components:** Modal, Drawer, Dropdown, Tabs, Accordion, Toast, Badge, … (SSR + runtime modules)
- **CLI & scaffolding:** `serixa new`, `make:*`, `docs`, `build`, `dev` (DX — independent of the renderer)
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
| 8.0 | Progressive Enhancement Foundation | Complete (`v0.8.0`) |
| 8.1 | Forms attribute isolation patch | Complete (`v0.8.1`) |
| 9.0 | Interactive Components | Complete (`v0.9.0`) |
| 10.0 | Developer Experience / CLI | Complete (`v0.10.0`) |
| 10.1 | Polish: performance, beginner docs, clearer errors | Complete (`v0.11.0`) |

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

> Looking for a full runnable app instead of a fragment? See the [Beginner path](#beginner-path) above — [docs/getting-started.md](docs/getting-started.md) and [docs/first-blog.md](docs/first-blog.md).

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
bin/
templates/
```

## CLI

```bash
composer install
php bin/serixa list
php bin/serixa new blog
```

## Documentation

**Start here:**

- [Getting started](docs/getting-started.md)
- [Build a blog in 30 minutes](docs/first-blog.md)
- [Build an admin dashboard shell](docs/first-dashboard.md)
- [Project structure](docs/project-structure.md)
- [Deployment](docs/deployment.md)
- [FAQ](docs/faq.md)
- [Troubleshooting](docs/troubleshooting.md)

**Reference:**

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
- [Events](docs/events.md)
- [Progressive Enhancement](docs/progressive-enhancement.md)
- [Interactive Components](docs/interactive-components.md)
- [Runtime Modules](docs/runtime-modules.md)
- [CLI](docs/cli.md)
- [Scaffolding](docs/scaffolding.md)
- [Project generator](docs/project-generator.md)
- [Templates](docs/templates.md)

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
