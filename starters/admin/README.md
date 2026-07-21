# SERIXA Admin Starter

The official SERIXA Admin Starter — a production-ready-looking **administration
panel scaffold**, built entirely from [`serixa/framework`](../../framework),
[`serixa/admin`](../../packages/admin) and [`serixa/icons`](../../packages/icons)
widgets.

> **Fake data disclaimer.** There is no database, ORM, authentication, or
> real CRUD in this starter. Every page renders from static PHP arrays in
> `App\Data\Fixtures`. Forms post to `#` and do not persist anything. This is
> intentional — the starter exists to demonstrate composing a realistic admin
> UI purely from SERIXA widgets, ready for you to wire up to your own data
> layer.

## Installation

```bash
cd starters/admin
composer install
composer start
# → http://localhost:8000
```

`composer start` runs `php -S localhost:8000 -t public`. The three SERIXA
packages are resolved via local Composer **path repositories**
(`../../framework`, `../../packages/icons`, `../../packages/admin`).

Refresh the copied client runtime after upgrading the framework:

```bash
php bin/sync-runtime.php
```

## Folder structure

```
starters/admin/
  app/
    Data/Fixtures.php      Static fake data for every domain page
    Support/Presenter.php  Status → StatusBadge helpers
    DocumentFactory.php    Document / SEO / Tailwind CDN / client runtime
  layouts/
    PanelLayout.php        Shared AppShell: sidebar, TopBar, FooterBar
  pages/                   One class per screen (see Implemented pages below)
  routes/Web.php           `?page=` → page class map
  components/              App-specific widgets (empty by default)
  config/app.php           Plain config array
  public/                  Front controller, app.css, runtime JS
  assets/                  Optional static assets you add later
  storage/                 Reserved for future file-based cache
  tests/SmokeTest.php      Renders every known page
  bin/smoke-check.php      CLI smoke render loop
  bin/sync-runtime.php     Copies serixa.js from the framework package
```

## How routing works

`public/index.php` reads `?page=` (default `dashboard`), sends HTTP 404 when
unknown, and renders `App\Web::document()`:

```php
$page = $_GET['page'] ?? 'dashboard';

if (! Web::isKnown($page)) {
    http_response_code(404);
}

echo (new Renderer())->render(Web::document($page, $_GET));
```

Every page exposes `document(array $query = []): Document`.

## Creating pages

1. Add fixture data in `App\Data\Fixtures` if needed.
2. Create `pages/YourPage.php` that builds a `Serixa\Admin\Page\Page` and
   returns `PanelLayout::wrap('your-key', $page)`.
3. Register the key in `routes/Web.php`.
4. Add a sidebar item in `layouts/PanelLayout.php`.
5. Cover it in `tests/SmokeTest.php`.

## Replacing fake data

Replace `App\Data\Fixtures` method bodies with repository/service calls (PDO,
Eloquent, API clients — your choice). Keep page classes focused on composition:
fixtures → presenter badges → admin widgets → `PanelLayout::wrap()`.

## Adding business logic

Keep domain rules **outside** SERIXA packages:

- Auth, tenancy, billing, and validation live in your app (or your own packages).
- Starters are the composition root; packages ship reusable UI only.
- Prefer injecting services into page composers once you add a container.

## Theme & styling

Presentation resolves through `ThemeInterface` / `AdminTheme`. Do not sprinkle
Tailwind utility classes in starter PHP — themes emit classes. `public/app.css`
is limited to a few browser tweaks.

## Deployment

1. Point the web server document root at `public/`.
2. Run `composer install --no-dev --optimize-autoloader`.
3. Replace path repositories with Packagist (or your private registry) versions
   of `serixa/framework`, `serixa/admin`, and `serixa/icons`.
4. Wire real auth, data, and persistence before production traffic.

## Testing & QA

```bash
composer test
composer qa          # phpunit + phpstan + php-cs-fixer dry-run
php bin/smoke-check.php
```

## Packages used

| Package | Role |
|---------|------|
| `serixa/framework` | Document, Renderer, DataTable, Form controls, Avatar, client runtime |
| `serixa/admin` | AppShell / AdminLayout, Page, metrics, table chrome, UserMenu, badges |
| `serixa/icons` | Inline SVG icons consumed by admin widgets |

## Implemented pages

Dashboard · Users · Roles · Permissions · Departments · Products · Orders ·
Customers · Invoices · Reports · Analytics · Notifications · Audit Logs ·
Settings · Profile · 404
