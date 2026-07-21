# SERIXA CLI (`v0.10.0`)

The CLI is a **developer tool**, independent from the HTML render pipeline.

```bash
vendor/bin/serixa
# or
php bin/serixa
```

## Global commands

| Command | Purpose |
|---------|---------|
| `serixa` | Banner + usage |
| `serixa version` / `-V` | Print CLI version |
| `serixa help [command]` | Help text |
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

See also [scaffolding.md](scaffolding.md), [project-generator.md](project-generator.md), [templates.md](templates.md).
