# Contributing

Thanks for helping build SERIXA.

## Rules

- Do not break public APIs in patch releases. See [docs/VERSIONING.md](docs/VERSIONING.md) and [docs/DEPRECATION.md](docs/DEPRECATION.md).
- Prefer composition over inheritance.
- Keep Tailwind/utility classes inside themes and tokens — not in widgets.
- One responsibility per class.
- Mark non-SemVer types with `@internal`.
- Add or update PHPUnit tests for behavior changes.
- Update `CHANGELOG.md` for user-visible changes.
- Do not add routing, auth, ORM, or CLI unless a milestone explicitly calls for it.
- Record significant decisions as ADRs in `docs/adr/`.

## Setup

```bash
composer install
composer qa
php examples/basic.php
```

## Pull requests

- Keep diffs focused.
- Document architectural decisions in `docs/` when introducing new seams.
- Mention BC impact in the PR description.
- Ensure CI (tests + PHPStan + CS) is green.
