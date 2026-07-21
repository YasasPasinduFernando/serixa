# SERIXA CLI (`serixa/cli` v0.1.0)

The CLI is a **separate Composer package** from the runtime engine.

| Package | Role |
|---------|------|
| `serixa/framework` | Widgets, renderer, theme, document, `serixa.js` |
| `serixa/cli` | `serixa new`, `make:*`, `docs`, `build`, `dev` |

```bash
composer require --dev serixa/cli
vendor/bin/serixa version
```

Monorepo:

```bash
cd packages/cli
composer install
php bin/serixa version
```

Namespace: **`Serixa\Cli\`**. Templates live in `packages/cli/templates/`.

New to SERIXA? [getting-started.md](getting-started.md) and [first-blog.md](first-blog.md).

## Global commands

| Command | Purpose |
|---------|---------|
| `serixa` | Banner + usage + quick start |
| `serixa version` / `--version` / `-V` | Print CLI version (fast path) |
| `serixa help [command]` | Help |
| `serixa list` | List commands |

## Project & generators

| Command | Purpose |
|---------|---------|
| `serixa new blog` | Scaffold blog / crm / ecommerce (or `--type=`) |
| `serixa make:page Dashboard` | Create `pages/DashboardPage.php` |
| `serixa make:component UserCard` | Create `components/UserCard.php` |
| `serixa make:layout Admin` | Create `layouts/AdminLayout.php` |
| `serixa make:theme Dark` | Create `themes/DarkTheme.php` |
| `serixa make:plugin Charts` | Create `plugins/ChartsPlugin.php` |

## DX tooling

| Command | Purpose |
|---------|---------|
| `serixa docs` | Write `docs/generated/*` |
| `serixa build` | Tokens, runtime copy, manifest, docs cache |
| `serixa dev --once` | Single watch cycle |

## Plugin commands

```php
use Serixa\Cli\Plugin;

Plugin::registerCommand(new MyCommand());
```

See also [scaffolding.md](scaffolding.md), [project-generator.md](project-generator.md), [templates.md](templates.md), [monorepo.md](monorepo.md).
