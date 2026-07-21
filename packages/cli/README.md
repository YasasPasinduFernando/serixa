# SERIXA CLI

Composer package: **`serixa/cli`**

Official scaffolding and DX tooling for SERIXA. Independent from the runtime engine (`serixa/framework`).

## Install

```bash
composer require --dev serixa/cli
```

In this monorepo:

```bash
cd packages/cli
composer install
php bin/serixa version
```

## Commands

| Command | Purpose |
|---------|---------|
| `serixa new blog` | Scaffold an application |
| `serixa make:page` / `make:component` / `make:layout` / `make:theme` / `make:plugin` | Generators |
| `serixa docs` | Write `docs/generated/*` |
| `serixa build` | Tokens, runtime copy, manifest |
| `serixa dev` | Watch rebuild |

Templates live in this package under `templates/`.

## Architecture

- Namespace: `Serixa\Cli\`
- Depends on `serixa/framework` for `ClientRuntime`, theme/docs introspection
- Does **not** ship widgets or the HTML renderer

See [docs/cli.md](../../docs/cli.md) and [docs/monorepo.md](../../docs/monorepo.md).
