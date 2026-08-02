# SERIXA

[![CI](https://github.com/YasasPasinduFernando/serixa/actions/workflows/ci.yml/badge.svg)](https://github.com/YasasPasinduFernando/serixa/actions/workflows/ci.yml)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/releases/8.2/en.php)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Docs](https://img.shields.io/badge/docs-serixa.yasaboy.com-0f172a)](https://serixa.yasaboy.com)

**SERIXA** is a Flutter-inspired PHP UI engine.

Write PHP widgets. SERIXA renders semantic HTML, applies a theme, compiles offline CSS, and ships a tiny progressive-enhancement runtime. It is **not** a JavaScript framework, **not** a Laravel/Symfony alternative, and **not** a template engine.

> Compose a **widget tree** in PHP → walk it through a **render pipeline** → emit clean HTML + compiled CSS.

**Documentation:** [https://serixa.yasaboy.com](https://serixa.yasaboy.com)  
**Source:** [github.com/YasasPasinduFernando/serixa](https://github.com/YasasPasinduFernando/serixa)

---

## Why SERIXA exists

Most PHP UIs either become a tangle of templates or push the entire interface into a SPA. SERIXA keeps the UI in PHP — structured like Flutter widgets — while staying server-rendered and progressive.

You get:

- **Widget Tree** — nest `Column`, `Row`, `Card`, `Button`, … as real PHP objects
- **Semantic HTML renderer** — accessible markup by default
- **Offline CSS compiler** — Theme Compiler → `/assets/serixa.css` (no Tailwind CDN)
- **Design tokens + themes** — swap themes without rewriting widgets
- **Tiny runtime** — optional `serixa.js` for modals, tabs, drawers when you need them

---

## Quick start

```bash
composer require serixa/framework
```

```php
<?php

declare(strict_types=1);

use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

require 'vendor/autoload.php';

$document = Document::make()
    ->title('Hello SERIXA')
    ->description('My first widget tree')
    ->body(
        Column::make([
            Text::make('Hello, SERIXA')->as('h1')->size('xl')->weight('bold'),
            Button::make('Continue')->primary(),
        ])->gap(3),
    );

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render($document);
```

Scaffold an app with the CLI (monorepo or after Packagist publish):

```bash
composer require --dev serixa/cli
vendor/bin/serixa new blog
cd blog && composer install
vendor/bin/serixa build
php -S localhost:8000 -t public
```

Official presets: `admin` · `blog` · `ecommerce` · `crm` · `cms` · `saas`

---

## Widget tree & rendering pipeline

```text
PHP widgets (tree)
        │
        ▼
  Document + Assets
        │
        ▼
  Render pipeline (theme · a11y · HTML)
        │
        ▼
  Semantic HTML + serixa.css + optional serixa.js
```

Widgets are PHP objects. The renderer walks the tree, resolves theme classes / tokens, and emits HTML. CSS is compiled **offline** (`serixa build` / Theme Compiler). Interactive widgets register markers that the progressive-enhancement runtime mounts only when present.

---

## Installation

| Path | Guide |
|------|--------|
| Path / monorepo | [docs/installation.md](docs/installation.md) |
| Getting started | [docs/getting-started.md](docs/getting-started.md) |
| First blog tutorial | [docs/first-blog.md](docs/first-blog.md) |
| First dashboard | [docs/first-dashboard.md](docs/first-dashboard.md) |

**Requirements:** PHP **8.2+**, Composer 2.

Until `serixa/framework` is on Packagist, use a Composer path repository pointing at this monorepo’s `framework/` directory (see installation guide).

---

## Package ecosystem

This monorepo ships **`serixa/framework`** plus first-party packages under [`packages/`](packages/).

### Core

| Package | Role |
|---------|------|
| [`serixa/framework`](framework/) | Widget engine, Document, Renderer, Theme, StyleSheet, client runtime |

### UI kits

| Package | Role |
|---------|------|
| `serixa/icons` | Icon set |
| `serixa/forms` | Forms + validation helpers |
| `serixa/admin` | Admin shell / panels |
| `serixa/charts` | SSR chart widgets |
| `serixa/calendar` | Calendar widgets |
| `serixa/auth-ui` | Login / register / profile **UI** (no session layer) |

### Developer tools

| Package | Role |
|---------|------|
| `serixa/cli` | `serixa new`, `make:*`, `build`, `docs`, doctor / profile |
| `serixa/devtools` | Dev-only inspectors / overlays |

### Official domain packages (v0.1.0)

Enterprise, commerce, CMS, files, security, and more — see the full list in [docs/ecosystem-roadmap.md](docs/ecosystem-roadmap.md) and the live [Packages](https://serixa.yasaboy.com/?page=packages) catalog.

### Experimental adapters (v0.1.0)

Search, AI, notifications, OAuth, payments, and cloud storage packages ship as **client-port adapters** with `Fake*` implementations for offline tests. You provide real HTTP/SDK clients in production. Labeled **Experimental** on the docs site until they mature.

Local development uses Composer **path** repositories and `*@dev` constraints inside the monorepo.

---

## Starter kits

| Starter | Status |
|---------|--------|
| [`starters/blog`](starters/blog/) | Full |
| [`starters/admin`](starters/admin/) | Full |
| [`starters/crm`](starters/crm/) | Full |
| [`starters/ecommerce`](starters/ecommerce/) | Scaffold |
| [`starters/portfolio`](starters/portfolio/) | Scaffold |
| [`starters/docs`](starters/docs/) | Scaffold |

---

## Architecture

Monorepo layout:

```text
SERIXA/
  framework/     # serixa/framework (production package)
  packages/      # Official first-party packages
  starters/      # Starter kits
  website/       # Documentation site (SERIXA SSR app)
  docs/          # Markdown documentation source
  playground/    # Local demos
  tools/         # Scripts & release helpers
```

Deeper reading: [docs/architecture.md](docs/architecture.md) · [docs/monorepo.md](docs/monorepo.md) · [docs/API_STABILITY.md](docs/API_STABILITY.md) · [docs/adr/](docs/adr/)

---

## Documentation

| Topic | Link |
|-------|------|
| Live docs site | [https://serixa.yasaboy.com](https://serixa.yasaboy.com) |
| Installation | [docs/installation.md](docs/installation.md) |
| Getting started | [docs/getting-started.md](docs/getting-started.md) |
| Styling / Theme Compiler | [docs/styling.md](docs/styling.md) |
| Rendering | [docs/rendering-engine.md](docs/rendering-engine.md) |
| CLI | [docs/cli.md](docs/cli.md) |
| FAQ | [docs/faq.md](docs/faq.md) |
| Ecosystem roadmap | [docs/ecosystem-roadmap.md](docs/ecosystem-roadmap.md) |
| Framework review backlog | [docs/framework-review.md](docs/framework-review.md) |

---

## Roadmap

- **Framework:** API-frozen at **v1.0.0** (additive-only).  
- **Ecosystem:** Phases 1–11 complete locally (enterprise → CLI templates).  
- **Next:** Phase 12 specialized starters (booking, school, hospital, …).  

Details: [docs/ecosystem-roadmap.md](docs/ecosystem-roadmap.md) · root [ROADMAP.md](ROADMAP.md)

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) and [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md).

- Issues: [GitHub Issues](https://github.com/YasasPasinduFernando/serixa/issues)  
- Security: [SECURITY.md](SECURITY.md) — **do not** file public issues for vulnerabilities  
- Conduct: [CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md) 

---

## License

MIT — see [LICENSE](LICENSE).
