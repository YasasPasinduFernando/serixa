# SERIXA

**SERIXA** is a Flutter-inspired PHP UI ecosystem: write PHP widgets, get clean semantic HTML.

> Write PHP. SERIXA renders HTML, applies Tailwind through a theme, and ships a tiny progressive-enhancement runtime — not a JavaScript framework.

This repository is a **monorepo**. The production package is [`framework/`](framework/) (`serixa/framework`). Optional packages, starter kits, playground apps, and tooling live alongside it so they can later split into independent Composer packages **without** renaming namespaces or refactoring APIs.

See [docs/monorepo.md](docs/monorepo.md) for architecture, versioning, and the future split strategy.

**New here?** Start with the [Installation Guide](docs/installation.md).

**Documentation website:** [`website/`](website/) — official SSR docs site (`composer start` inside `website/`).

## Repository layout

```text
SERIXA/
  framework/          # serixa/framework (production package)
  packages/           # Optional packages (skeletons)
  starters/           # Starter kit skeletons
  playground/         # Local demos
  tools/              # Build / release / benchmark helpers
  docs/               # Ecosystem + framework documentation
  validation/         # Real-app validation fixtures
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
```

Or: `composer require --dev serixa/cli` in an application.

## Packages

Optional packages under [`packages/`](packages/) — **skeletons only** (no implementation yet):

| Package | Composer name | Intent |
|---------|---------------|--------|
| icons | `serixa/icons` | Icon set / helpers |
| charts | `serixa/charts` | Chart widgets |
| calendar | `serixa/calendar` | Calendar widgets |
| admin | `serixa/admin` | Admin UI kit |
| auth-ui | `serixa/auth-ui` | Auth UI surfaces |
| devtools | `serixa/devtools` | DX helpers |

Each package `composer.json` already path-requires `serixa/framework` for local development (no Packagist needed).

## Starter kits

Clean project skeletons under [`starters/`](starters/):

| Starter | Purpose |
|---------|---------|
| blog | Content / journal apps |
| admin | Admin shells |
| crm | CRM-style apps |
| ecommerce | Catalog / storefront shells |
| portfolio | Portfolio sites |
| docs | Documentation sites |

Structure only (`app/`, `components/`, `layouts/`, `pages/`, `public/`, `config/`). No generated pages yet.

## Playground

[`playground/serixa-demo`](playground/serixa-demo) — interactive demo app for manual exploration.

## Contribution

1. Read [docs/monorepo.md](docs/monorepo.md) and [CONTRIBUTING.md](CONTRIBUTING.md).
2. Work in `framework/` for core changes; keep public APIs stable.
3. Package/starter work stays in their folders; do not invent APIs until a package is scheduled.
4. Run `composer qa` from the repo root (or `framework/`) before opening a PR.

## Roadmap

See [ROADMAP.md](ROADMAP.md). Framework is feature-complete for pre-1.0 UI work; upcoming effort focuses on ecosystem packages, starters, and polish.

## Future package split

When a package is ready:

1. Extract `packages/{name}` (or `framework/`) into its own repository.
2. Publish to Packagist under the same Composer name.
3. Replace path repositories with version constraints.
4. **No namespace or public API refactor** — that is the point of this layout.

## License

MIT — see [LICENSE](LICENSE) and [framework/LICENSE](framework/LICENSE).
