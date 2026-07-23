# SERIXA CLI (`serixa/cli` v0.2.0)

The CLI is a **separate Composer package** from the runtime engine.

| Package | Role |
|---------|------|
| `serixa/framework` | Widgets, renderer, theme, document, `serixa.js` |
| `serixa/cli` | Scaffolding, build, doctor, profile, inspect |

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
| `serixa new admin` | Scaffold admin / blog / ecommerce / crm / cms / saas (or `--type=`) |
| `serixa make:page Dashboard` | Create `pages/DashboardPage.php` |
| `serixa make:component UserCard` | Create `components/UserCard.php` |
| `serixa make:layout Admin` | Create `layouts/AdminLayout.php` |
| `serixa make:theme Dark` | Create `themes/DarkTheme.php` |
| `serixa make:plugin Charts` | Create `plugins/ChartsPlugin.php` |

## DX tooling

| Command | Purpose |
|---------|---------|
| `serixa docs` | Write `docs/generated/*` |
| `serixa build` | Offline CSS (`serixa.css`), runtime copy, manifest, docs cache |
| `serixa dev --once` | Single watch cycle (omit `--once` to poll) |
| `serixa doctor` | PHP / extensions / framework CSS / project asset checks |
| `serixa inspect` | Print cwd, stylesheet URL, and a theme sample |
| `serixa benchmark` | Sample ThemeCompiler / StyleCompiler / Renderer timings |
| `serixa profile` | Cold/warm profile for caches, theme map, Renderer, Document |
| `serixa cache:clear` | Clear in-process ClassMerger / ThemeCompiler / pipeline caches |

Theme and asset compilation are performed by `serixa build` (and `serixa dev`). There are no separate `theme:compile` / `asset:compile` commands.

## Plugin commands

```php
use Serixa\Cli\Plugin;

Plugin::registerCommand(new MyCommand());
```

See also [scaffolding.md](scaffolding.md), [project-generator.md](project-generator.md), [templates.md](templates.md), [monorepo.md](monorepo.md), [accessibility.md](accessibility.md).
