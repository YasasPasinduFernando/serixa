# SERIXA v1.0.0 Release Notes

**Release date:** 2026-07-22  
**Status:** Stable

SERIXA 1.0.0 is the first stable release of the Flutter-inspired PHP UI framework.

## Highlights

- Offline Theme Compiler and `/assets/serixa.css` pipeline (no Tailwind CDN)
- Full widget catalog with semantic HTML + progressive enhancement runtime
- Official packages: Admin, Forms, Icons, Charts, Calendar, Auth UI, DevTools, CLI
- Production DX: `serixa doctor`, `inspect`, `benchmark`, `profile`, `cache:clear`, `build`, `dev`
- Accessibility inventory + ARIA contracts for core interactive widgets
- Packagist-ready metadata (`homepage`, `support`), `SECURITY.md`, `.gitattributes`

## Requirements

- PHP 8.2+
- Extensions: `json`, `mbstring`

## Install

```bash
composer require serixa/framework
composer require --dev serixa/cli
```

## Upgrade from RC

If you are on `v1.0.0-rc5` or earlier RCs:

1. Run `composer update serixa/framework`
2. Run `serixa build` to regenerate CSS
3. Prefer `Serixa\Styling\StyleSheet` over the deprecated Assets alias
4. Prefer `WidgetInterface` child typing on layout/display widgets

No intentional public API breaks were introduced between rc5 and 1.0.0.

## Quality gate (monorepo)

| Metric | Result |
|--------|--------|
| PHPUnit tests | 633+ |
| Assertions | 5000+ |
| `composer qa` | Green for framework, packages, starters (admin/blog), website |

## Security

Report vulnerabilities privately — see [SECURITY.md](../SECURITY.md).

## License

MIT
