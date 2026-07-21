# SERIXA

**SERIXA** is a Flutter-inspired PHP UI framework: write PHP widgets, get clean semantic HTML.

> Write PHP. SERIXA renders HTML, applies Tailwind through a theme, handles responsive layout, and cares about accessibility.

PHP namespace today: `NovaPHP\` (kept for backward compatibility). The product name is **SERIXA**.

## Vision

Frontend development should feel like Flutter — developers focus on business logic, not HTML, Tailwind classes, or JavaScript boilerplate for common UI.

SERIXA is not a Laravel clone and not a React clone. It is a **components-first PHP UI engine**.

## Features

- Fluent, chainable widgets (`Button::make('Save')->primary()->large()`)
- Layout widgets: `Row`, `Column`, `Center`, `Padding`, `Spacer`, `Expanded`, `SizedBox`, `Stack`, `Divider`
- Theme seam + design tokens (swap Tailwind/Bootstrap later without changing widgets)
- Widget tree + lifecycle hooks (foundation for DevTools / interactive UI)
- Memory-only state manager (prepared for Modal, Tabs, Accordion)
- Accessibility helpers (`AccessibilityBuilder`)
- Escaped output by default
- PSR-4, PHP 8.2+, PHPUnit-tested

## Status

| Milestone | Name | Status |
|-----------|------|--------|
| 1 | Rendering engine | Complete |
| 2 | Layout engine + theme | Complete |
| 3 | Framework foundations | Complete (`v0.3.0`) |

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
use NovaPHP\Component\Button;
use NovaPHP\Component\Center;
use NovaPHP\Component\Column;
use NovaPHP\Component\SizedBox;
use NovaPHP\Component\Text;
use NovaPHP\Rendering\Renderer;

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

## Example

```bash
php examples/basic.php
php examples/foundations.php
```

## Project structure

```text
src/
  Accessibility/   ARIA helpers
  Component/       Concrete widgets (+ Component BC alias)
  Contracts/       Interfaces
  Event/           Event contracts (prep only)
  Rendering/       Renderer (tree + lifecycle)
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

- [Rendering engine](docs/rendering-engine.md) (Milestone 1)
- [Layout engine](docs/layout-engine.md) (Milestone 2)
- [Framework foundations](docs/framework-foundations.md) (Milestone 3)
- [Architecture overview](docs/architecture.md)

## Roadmap

See [ROADMAP.md](ROADMAP.md).

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

1. Keep public APIs stable unless a major version says otherwise.
2. Prefer composition over inheritance.
3. Put CSS class maps in themes/tokens — not in widgets.
4. Add PHPUnit coverage for new behavior.
5. Run `composer test` before opening a PR.

## License

MIT — see [LICENSE](LICENSE).
