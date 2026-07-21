# SERIXA CLI (`v0.11.1`)

New to the CLI? [docs/getting-started.md](getting-started.md) and [docs/first-blog.md](first-blog.md) walk through `new` → `make:page` → routing end to end. This page is the reference.

Monorepo note: the CLI binary lives at `framework/bin/serixa`. From the framework package root:

```bash
php bin/serixa
```

Or, once installed as a dependency:

```bash
vendor/bin/serixa
```

See [monorepo.md](monorepo.md).

The CLI is a **developer tool**, independent from the HTML render pipeline.

Running `serixa` with no arguments prints a quick-start banner (`serixa new blog`, `composer install`, `php -S`) and points at `docs/getting-started.md`.

## Global commands

| Command | Purpose |
|---------|---------|
| `serixa` | Banner + usage + quick start |
| `serixa version` / `--version` / `-V` | Print CLI version (fast path — skips command registration) |
| `serixa help [command]` | Help text; with no argument, also lists commands and links the beginner docs |
| `serixa list` | List registered commands |

## Project & generators

| Command | Purpose |
|---------|---------|
| `serixa new blog` | Scaffold blog / crm / ecommerce (or custom + `--type=`) |
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
| `serixa dev --once` | Single watch cycle (omit `--once` to poll) |

## Plugin commands

```php
use Serixa\Console\Plugin;

Plugin::registerCommand(new MyCommand());
```

## Notes

- `serixa new` warns (but doesn't block) if you run it from inside the SERIXA framework tree — see [Path-repository recursion](troubleshooting.md#path-repository-recursion).
- `make:*` commands never overwrite an existing file; re-running one for the same name fails with a clear "refusing to overwrite" message — see [troubleshooting.md](troubleshooting.md#duplicate-make-commands).
- `serixa help <command>` prints usage examples for that command (e.g. `serixa help new`).

See also [scaffolding.md](scaffolding.md), [project-generator.md](project-generator.md), [templates.md](templates.md), and the beginner path: [getting-started.md](getting-started.md), [first-blog.md](first-blog.md), [first-dashboard.md](first-dashboard.md).
