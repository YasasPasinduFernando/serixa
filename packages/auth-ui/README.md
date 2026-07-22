# SERIXA Auth UI

Official authentication **UI** for SERIXA — login, register, reset, 2FA, and
magic-link cards with layouts. No sessions, cookies, guards, passwords hashing,
JWT, or OAuth.

Depends on `serixa/framework`, `serixa/forms`, `serixa/icons`, and `serixa/admin`
(for theme stacking only).

## Install (monorepo)

```bash
cd packages/auth-ui
composer install
composer qa
```

## Quick start

```php
use Serixa\AuthUi\Layout\AuthLayout;
use Serixa\AuthUi\Pages\LoginCard;
use Serixa\AuthUi\Components\BrandLogo;

AuthLayout::make()
    ->card(LoginCard::make()->action('/login')->brand(BrandLogo::make('Acme')));
```

Point `action()` at your Laravel / Symfony / Slim / custom endpoint. Implement
`Serixa\Forms\Contracts\CsrfProvider` in the app.

See [docs/auth-ui.md](docs/auth-ui.md).
