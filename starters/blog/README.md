# SERIXA Starter — Blog

Clean starter skeleton. No generated pages yet — add your own with the SERIXA CLI or by hand.

## Setup

```bash
composer install
php -S localhost:8000 -t public
```

`serixa/framework` is resolved via a local path repository (`../../framework`).

## Layout

| Path | Purpose |
|------|---------|
| `app/` | Application helpers |
| `components/` | App widgets |
| `layouts/` | Shared chrome |
| `pages/` | Page documents |
| `public/` | Front controller + assets |
| `config/` | Configuration |

## Status

Structure only. Implement pages in a future milestone.
