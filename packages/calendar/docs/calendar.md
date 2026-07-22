# SERIXA Calendar

`serixa/calendar` is the official UI-only calendar package for SERIXA.

It does **not** provide storage, recurrence engines, Google/Outlook sync, databases,
reminders, or notifications. Applications own event persistence.

Depends on `serixa/framework` and `serixa/icons`.

## Architecture

### Why calendar belongs in a package

Calendars are optional product UI. Many SERIXA apps never schedule events. A
dedicated package keeps the core framework lean and lets starters/apps opt in.

### Why calendar must not live in `serixa/framework`

Framework owns document, layout, controls, theme contracts, and progressive
enhancement primitives. Month grids, schedulers, pickers, and `calendar.*` theme
keys are product widgets. Putting them in framework would force every consumer
to carry scheduling UI they may never use.

### How applications inject events

Apps query their own store (DB, API, CSV, static arrays) and pass data in:

```php
use Serixa\Calendar\Component\Calendar;
use Serixa\Calendar\Event\Event;

Calendar::make()
    ->today('2026-07-22')
    ->focus('2026-07-01')
    ->view('month')
    ->events([
        Event::make('Demo')->start('2026-07-22 10:00')->end('2026-07-22 11:00')->color('#0284c7'),
        ['title' => 'Ship', 'start' => '2026-07-25', 'allDay' => true],
    ]);
```

Supported inputs: `Event` objects, arrays, JSON strings, generators, `Traversable`,
objects with `toArray()`, callables returning any of the above.

### SSR strategy

1. Server renders semantic HTML (`role="grid"` month tables, agenda lists, native date/time inputs).
2. Event chips and cards are real links/text — readable without JavaScript.
3. Attributes (`data-serixa-calendar*`) mark roots for enhancement.

### Progressive enhancement

1. Load `serixa.js`, then `assets/calendar.js` (deferred, in that order).
2. `CalendarRuntime::register($assets)` adds the script URL.
3. `Serixa.module('calendar')` adds keyboard navigation, day selection, range drag highlight, popover reveal, and aria-live announcements.
4. **No hydration. No virtual DOM.** Markup is not replaced.

Without JS, native inputs and tables remain usable.

## Widget catalog

| Widget | Class | Role |
|--------|--------|------|
| Calendar | `Calendar` | Shell + toolbar + view |
| Mini calendar | `MiniCalendar` | Compact month |
| Month / Week / Day / Agenda | `MonthView`, `WeekView`, `DayView`, `AgendaView` | Views |
| Timeline | `TimelineCalendar` | Horizontal bars |
| Resources | `ResourceCalendar` | Resource × day |
| Scheduler | `Scheduler` | Booking/admin composite |
| Date / range / time / datetime | `DatePicker`, `DateRangePicker`, `TimePicker`, `DateTimePicker` | Pickers |
| Event UI | `EventCard`, `EventBadge`, `EventPopover`, `EventLegend` | Event chrome |
| Toolbar | `CalendarToolbar` | Nav + view switcher |

## Data API

```php
Event::make('Standup')
    ->title('Standup')
    ->start('2026-07-22 09:00')
    ->end('2026-07-22 09:15')
    ->color('#0ea5e9')
    ->url('/events/1')
    ->resource('room-a')
    ->allDay(false);
```

## Theme

```php
use Serixa\Calendar\Theme\CalendarTheme;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

ThemeManager::set(new CalendarTheme(new DefaultTheme()));
```

Keys under `calendar.*` (root, toolbar, grid, day, event, agenda, picker, …).
Decorator only — no Tailwind literals inside widgets.

## Runtime

```php
use Serixa\Calendar\Runtime\CalendarRuntime;

$document->assets()->js('/assets/serixa.js', ['defer' => true]);
CalendarRuntime::register($document->assets(), '/assets/calendar/calendar.js');
```

## Accessibility

- ARIA grid / gridcell / selected / current date
- Keyboard arrows + Enter/Space for day selection
- Screen reader summaries via `summary()` and aria-live
- High-contrast styling via theme tokens
- Native date/time inputs remain available without JS

## Examples

See `packages/calendar/examples/` — booking, CRM schedule, admin planner, team calendar, project timeline, personal agenda.

```bash
cd packages/calendar
php examples/booking.php
```

## Package layout

```
packages/calendar/
  src/Component|Event|View|Theme|Contracts|Support|Exceptions|Runtime/
  assets/calendar.js
  docs/
  tests/
  examples/
```
