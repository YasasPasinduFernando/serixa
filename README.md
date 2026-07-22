# SERIXA

**SERIXA** is a Flutter-inspired PHP UI ecosystem: write PHP widgets, get clean semantic HTML.

> Write PHP. SERIXA renders HTML, applies a theme, compiles offline CSS, and ships a tiny progressive-enhancement runtime — not a JavaScript framework.

This repository is a **monorepo**. The production package is [`framework/`](framework/) (`serixa/framework`). Optional packages, starter kits, playground apps, and tooling live alongside it.

See [docs/monorepo.md](docs/monorepo.md) for architecture, versioning, and the future split strategy.

**New here?** Start with the [Installation Guide](docs/installation.md).

**Documentation website:** [`website/`](website/) — official SSR docs site (`composer start` inside `website/`).

## Documentation

| Guide | Link |
|-------|------|
| Installation | [docs/installation.md](docs/installation.md) |
| Getting started | [docs/getting-started.md](docs/getting-started.md) |
| Styling / Theme Compiler | [docs/styling.md](docs/styling.md) |
| Assets | [docs/assets.md](docs/assets.md) |
| CLI | [docs/cli.md](docs/cli.md) |
| Deployment | [docs/deployment.md](docs/deployment.md) |
| Troubleshooting | [docs/troubleshooting.md](docs/troubleshooting.md) |
| Performance (v1) | [docs/performance-v1.md](docs/performance-v1.md) |
| FAQ | [docs/faq.md](docs/faq.md) |

## Repository layout

```text
SERIXA/
  framework/          # serixa/framework (production package)
  packages/           # Official optional packages
  starters/           # Starter kits
  playground/         # Local demos
  tools/              # Build / release / benchmark helpers
  docs/               # Ecosystem + framework documentation
  validation/         # Real-app validation fixtures
  website/            # Documentation website
  .github/            # CI
```

## Framework

The core UI engine lives in [`framework/`](framework/). Namespace: **`Serixa\`**.

```bash
cd framework
composer install
composer qa
```

## CLI

Scaffolding lives in [`packages/cli`](packages/cli/) (`serixa/cli`).

```bash
cd packages/cli
composer install
php bin/serixa version
php bin/serixa new blog
cd blog
composer install
vendor/bin/serixa build
php -S localhost:8000 -t public
```

Or: `composer require --dev serixa/cli` in an application.

## Packages

Official packages under [`packages/`](packages/):

| Package | Composer name | Status |
|---------|---------------|--------|
| icons | `serixa/icons` | Icon set |
| charts | `serixa/charts` | Chart widgets |
| calendar | `serixa/calendar` | Calendar widgets |
| admin | `serixa/admin` | Admin UI kit |
| auth-ui | `serixa/auth-ui` | Auth UI surfaces |
| forms | `serixa/forms` | Form kit |
| devtools | `serixa/devtools` | DX helpers |
| cli | `serixa/cli` | Scaffolding CLI |

Each package path-requires `serixa/framework` for local development.

## Starter kits

| Starter | Purpose |
|---------|---------|
| blog | Content / journal apps (full starter) |
| admin | Admin shells (full starter) |
| crm | CRM-style apps (scaffold) |
| ecommerce | Catalog / storefront shells (scaffold) |
| portfolio | Portfolio sites (scaffold) |
| docs | Documentation sites (scaffold) |

## Offline CSS

SERIXA does **not** use the Tailwind Play CDN. Run `serixa build` to compile `/assets/serixa.css` via the Theme Compiler. See [docs/styling.md](docs/styling.md).

## License

MIT — see [LICENSE](LICENSE).
