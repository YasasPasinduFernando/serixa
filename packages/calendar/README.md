# SERIXA Calendar

Official calendar package for SERIXA — SSR calendar UI with progressive enhancement.
UI only: no storage, sync, recurrence engines, or notifications.

## Requirements

- PHP 8.2+
- `serixa/framework`
- `serixa/icons`

## Installation (monorepo)

```bash
cd packages/calendar
composer install
composer qa
```

## Quick start

```php
use Serixa\Calendar\Component\Calendar;
use Serixa\Calendar\Event\Event;
use Serixa\Calendar\Theme\CalendarTheme;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

ThemeManager::set(new CalendarTheme(new DefaultTheme()));

$calendar = Calendar::make()
    ->view('month')
    ->today('2026-07-22')
    ->events([
        Event::make('Launch')->start('2026-07-22 10:00')->end('2026-07-22 11:00'),
    ]);
```

See [docs/calendar.md](docs/calendar.md).
