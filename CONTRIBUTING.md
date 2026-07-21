# Contributing

Thanks for helping build SERIXA.

## Rules

- Do not break public APIs in minor/patch releases.
- Prefer composition over inheritance.
- Keep Tailwind/utility classes inside themes and tokens — not in widgets.
- One responsibility per class.
- Add or update PHPUnit tests for behavior changes.
- Update `CHANGELOG.md` under an Unreleased or version section.
- Do not add routing, auth, ORM, or CLI unless a milestone explicitly calls for it.

## Setup

```bash
composer install
composer test
php examples/basic.php
```

## Pull requests

- Keep diffs focused.
- Document architectural decisions in `docs/` when introducing new seams.
- Mention BC impact in the PR description.
