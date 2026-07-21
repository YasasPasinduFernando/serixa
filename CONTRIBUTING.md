# Contributing

Thanks for helping build SERIXA.

## Monorepo

This repository is an ecosystem monorepo. Read [docs/monorepo.md](docs/monorepo.md) first.

- Framework code: `framework/`
- Optional packages: `packages/` (skeletons until scheduled)
- Starters: `starters/` (structure only until scheduled)

## Rules

- Do not break public APIs in patch releases. See [docs/VERSIONING.md](docs/VERSIONING.md) and [docs/DEPRECATION.md](docs/DEPRECATION.md).
- Prefer composition over inheritance.
- Keep Tailwind/utility classes inside themes and tokens — not in widgets.
- One responsibility per class.
- Mark non-SemVer types with `@internal`.
- Add or update PHPUnit tests for behavior changes.
- Update `framework/CHANGELOG.md` for user-visible framework changes.
- Do not implement package/starter features unless a milestone explicitly calls for it.
- Record significant decisions as ADRs in `docs/adr/`.

## Setup

```bash
composer install          # root meta → installs framework deps
composer qa               # delegates to framework/
php framework/examples/basic.php
php framework/bin/serixa version
```

## Pull requests

- Keep diffs focused.
- Document architectural decisions in `docs/` when introducing new seams.
- Mention BC impact in the PR description.
- Ensure CI (framework tests + PHPStan + CS + skeleton checks) is green.
