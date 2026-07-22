# Contributing

Thanks for helping build SERIXA.

## Monorepo

This repository is an ecosystem monorepo. Read [docs/monorepo.md](docs/monorepo.md) first.

- Framework code: `framework/` (`serixa/framework`)
- Optional packages: `packages/*` (admin, forms, icons, charts, calendar, auth-ui, devtools, cli)
- Starters: `starters/admin` and `starters/blog` are full kits; others are scaffolds
- Docs site: `website/`

## Rules

- Do not break public APIs in patch releases. See [docs/VERSIONING.md](docs/VERSIONING.md) and [docs/DEPRECATION.md](docs/DEPRECATION.md).
- Prefer composition over inheritance.
- Keep Tailwind/utility classes inside themes and tokens — not in widgets.
- One responsibility per class.
- Mark non-SemVer types with `@internal`.
- Add or update PHPUnit tests for behavior changes.
- Update `framework/CHANGELOG.md` for user-visible framework changes.
- Record significant decisions as ADRs in `docs/adr/`.

## Setup

```bash
composer install          # root meta → installs framework deps
composer qa               # delegates to framework/
php framework/examples/basic.php
cd packages/cli && composer install && php bin/serixa version
```

Run `composer qa` inside any package you change (`framework`, `packages/*`, `website`, `starters/admin|blog`).

## Security

Do not open public issues for vulnerabilities. See [SECURITY.md](SECURITY.md).

## Pull requests

- Keep diffs focused.
- Document architectural decisions in `docs/` when introducing new seams.
- Mention BC impact in the PR description.
- Ensure CI (framework tests + PHPStan + CS) is green.
