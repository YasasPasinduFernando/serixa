# SERIXA DevTools

Official developer tools for SERIXA — inspectors, collectors, overlay, and CLI.
**Development only. Do not ship to production.**

## Requirements

- PHP 8.2+
- `serixa/framework`
- `serixa/cli`

## Installation (monorepo / require-dev)

```bash
cd packages/devtools
composer install
composer qa
```

In apps: `"serixa/devtools": "*@dev"` under `require-dev`, then:

```php
if (getenv('APP_ENV') !== 'production') {
    \Serixa\DevTools\DevTools::enable();
}
```

See [docs/devtools.md](docs/devtools.md).
