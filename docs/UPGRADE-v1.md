# Upgrade guide — toward SERIXA v1.0.0

## Prerequisites

- PHP ^8.2
- Composer 2
- Offline CSS build in every app (`serixa build`)

## Steps

1. Update path/Packagist constraints to the v1.0 candidate you are testing.
2. `composer update` in framework apps, starters, and the website.
3. Replace CDN registrations with `StyleSheet::register()`.
4. Run `vendor/bin/serixa build` (or `composer build` on the website).
5. Run `composer qa` in each package you ship.
6. Smoke-test dark mode + mobile layouts on docs, blog, and admin.

## Breaking changes

**None intended** for public widget / Document / Renderer APIs in this stabilization pass.

Deprecated (still available):

- `Serixa\Assets\StyleSheet` → `Serixa\Styling\StyleSheet`
- `Serixa\Component\Component` → prefer `Serixa\Widget\Widget` as the base type hint via `WidgetInterface`
- `Padding::__callStatic` factories → fluent `Padding::make()->all()`

## After upgrade

Read [KNOWN-ISSUES-v1.md](KNOWN-ISSUES-v1.md) and [RELEASE-CHECKLIST-v1.md](RELEASE-CHECKLIST-v1.md).
