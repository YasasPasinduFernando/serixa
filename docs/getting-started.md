# Getting started

This page is the entry point for new SERIXA users. It explains what SERIXA is, what it is not, and where to go next.

For a full walkthrough of Composer install, first page, rendering, widgets, themes, and layouts, see the **[Installation Guide](installation.md)**.

## What SERIXA is

SERIXA is a PHP UI framework. You write widgets in PHP; SERIXA renders clean HTML with Tailwind classes applied through a theme. Rendering is server-side (SSR) — there is no virtual DOM and no client-side hydration.

```php
use Serixa\Component\Button;
use Serixa\Component\Text;
use Serixa\Component\Column;
use Serixa\Rendering\Renderer;

$html = (new Renderer())->render(
    Column::make([
        Text::make('Hello, SERIXA')->as('h1')->size('xl')->weight('bold'),
        Button::make('Continue')->primary(),
    ]),
);
```

Every widget lives under the `Serixa\Component\` namespace (with a `Serixa\Widget\Widget` base class underneath). Rendering goes through `Serixa\Rendering\Renderer`. Full HTML pages go through `Serixa\Document\Document`.

## What SERIXA is not

Be clear about scope before you start:

- **No router.** SERIXA does not decide which page to show for a URL. Your app does that (see [first-blog.md](first-blog.md) for a small `match()` example).
- **No auth / sessions.** Login and profile pages are HTML only. Wire your own session/auth logic in your app.
- **No ORM or database layer.** SERIXA renders widgets; where the data comes from is up to you.
- **No Chart or Calendar widgets.** Not implemented yet. Use `EmptyState` or `Skeleton` as an honest placeholder if you need one today.
- **No virtual DOM / SPA state.** The client runtime (`assets/runtime/serixa.js`) is a small progressive-enhancement script, not a framework runtime.

If you keep this in mind, the rest of the framework is straightforward.

## Requirements

- PHP 8.2+
- Composer

## The fastest path to a running app

From a **clone of this monorepo**, install and run the CLI package:

```bash
cd packages/cli
composer install
php bin/serixa new blog
cd blog
composer install
php -S localhost:8000 -t public
```

Or from an app that already requires `serixa/cli`:

```bash
vendor/bin/serixa new blog
```

Prefer generating apps **outside** `framework/` (sibling folders like `starters/` or a directory outside the clone). See [Path-repository recursion](troubleshooting.md#path-repository-recursion).

Open http://localhost:8000. You now have a runnable SSR app with a home page, layout, and a few sample pages.

For the full 30-minute walkthrough (pages, layout, a card list, a contact form), read [first-blog.md](first-blog.md).

Monorepo overview: [monorepo.md](monorepo.md).
CLI reference: [cli.md](cli.md).

## Core concepts, in order

1. **Widgets** — PHP objects that render HTML. Built with a fluent `::make()` entry point and chained modifiers, e.g. `Button::make('Save')->primary()->large()`.
2. **Renderer** — `Serixa\Rendering\Renderer` turns a widget tree (or a `Document`) into an HTML string.
3. **Theme** — `Serixa\Theme\DefaultTheme` maps a widget + its state (props) to a list of Tailwind classes. Widgets don't hardcode CSS classes; the theme decides.
4. **Document** — `Serixa\Document\Document` builds a full HTML page: `<title>`, meta tags, registered CSS/JS assets, and a body of widgets.
5. **ClientRuntime** — `Serixa\Runtime\ClientRuntime::register()` wires up the small `serixa.js` script for progressive enhancement (modals, dropdowns, tabs, toasts, etc.). Optional; skip it if you don't use interactive widgets.
6. **CLI** — `serixa new`, `serixa make:page`, `serixa make:component`, `serixa make:layout` scaffold an application around the framework. The CLI is a developer tool; it never runs at request time.

## A minimal full page

```php
use Serixa\Component\Button;
use Serixa\Component\Text;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$document = Document::make()
    ->title('Hello')
    ->description('My first SERIXA page')
    ->body(
        Text::make('Hello, SERIXA')->as('h1')->size('xl')->weight('bold'),
        Button::make('Continue')->primary(),
    );

echo (new Renderer())->render($document);
```

## Where to go next

| Goal | Doc |
|------|-----|
| Install and render a first page | [installation.md](installation.md) |
| Build a full blog end-to-end | [first-blog.md](first-blog.md) |
| Build an admin dashboard shell | [first-dashboard.md](first-dashboard.md) |
| Understand the generated folder layout | [project-structure.md](project-structure.md) |
| Put an app on a real server | [deployment.md](deployment.md) |
| Common questions | [faq.md](faq.md) |
| Something broke | [troubleshooting.md](troubleshooting.md) |
| Full CLI reference | [cli.md](cli.md) |
| Widget-by-widget reference | [ui-foundation.md](ui-foundation.md), [data-display.md](data-display.md), [interactive-components.md](interactive-components.md), [layout-engine.md](layout-engine.md) |

## Getting help from the CLI itself

```bash
serixa help
serixa help new
serixa list
```

`serixa` with no arguments prints a quick-start banner with the same three commands shown above.
