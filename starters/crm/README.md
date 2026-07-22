# SERIXA CRM Starter

Official **CRM** application starter for the SERIXA ecosystem.

Composer: `serixa/starter-crm`

This is an **application**, not a package. It demonstrates a full sales CRM UI using only existing SERIXA packages — fixtures only (no database, auth, sessions, or ORM).

## Packages used

| Package | Role |
|---------|------|
| `serixa/framework` | Widgets, Document, Theme Compiler, offline CSS |
| `serixa/admin` | Shell layout, tables, metrics, breadcrumbs |
| `serixa/forms` | Profile / Settings forms |
| `serixa/icons` | Sidebar and action icons |
| `serixa/charts` | Dashboard / Analytics / Reports charts |
| `serixa/calendar` | Calendar month grid |

## Requirements

- PHP 8.2+
- Composer

## Installation (monorepo)

```bash
cd starters/crm
composer install
php bin/sync-runtime.php
composer build
composer start
# open http://localhost:8000
```

Routing uses `?page=`:

```text
http://localhost:8000/?page=dashboard
http://localhost:8000/?page=lead&id=1
```

Unknown pages return HTTP 404 and render the Not Found screen.

## Architecture

Mirrors `starters/admin` and `starters/blog`:

```text
app/DocumentFactory.php   # Document + StyleSheet + runtimes
app/Data/Fixtures.php     # Fake CRM data
app/Support/Presenter.php # Status → StatusBadge
layouts/PanelLayout.php   # AdminLayout chrome
routes/Web.php            # ?page= map
pages/*Page.php           # One class per screen
public/index.php          # Front controller
```

## Pages

Dashboard, Leads, Lead Details, Companies, Contacts, Opportunities, Pipeline, Tasks, Calendar, Meetings, Invoices, Reports, Analytics, Users, Roles, Settings, Profile, Notifications, 404.

## Theme / CSS

No Tailwind CDN. Run `composer build` to compile Theme Compiler output into `public/assets/serixa.css`.

## QA

```bash
composer qa
php bin/smoke-check.php
```

## Docs

- Ecosystem guide: [docs/starter-crm.md](../../docs/starter-crm.md)
- Framework review from this build: [docs/crm-framework-review.md](../../docs/crm-framework-review.md)

## License

MIT
