# SERIXA CRM Starter

Official CRM demo application for the SERIXA ecosystem. It validates that `serixa/framework`, `serixa/admin`, `serixa/forms`, `serixa/icons`, `serixa/charts`, and `serixa/calendar` can compose a production-shaped CRM without a framework redesign.

Location: `starters/crm`

## Installation

```bash
cd starters/crm
composer install
composer build
php -S 127.0.0.1:8090 -t public
```

Open `http://127.0.0.1:8090/?page=dashboard`.

Requirements: PHP 8.2+, Composer. Path repositories point at sibling packages under `packages/`.

## Routing

Routes live in `routes/Web.php` as `page => class` map entries. The front controller (`public/index.php`) reads `?page=`, defaults to `dashboard`, and returns HTTP 404 for unknown keys.

```php
$page = isset($_GET['page']) ? (string) $_GET['page'] : 'dashboard';
$class = Web::resolve($page) ?? NotFoundPage::class;
```

There is no Laravel/Symfony router. Adding a page means:

1. Create `pages/YourPage.php` implementing `PageContract`.
2. Register it in `routes/Web.php`.
3. Optionally add a sidebar item in `layouts/PanelLayout.php`.

## Layouts

`layouts/PanelLayout.php` wraps every page in `Serixa\Admin\AdminLayout` with:

- Brand + sidebar navigation (grouped by Sales / Pipeline / Finance / Insights / Workspace)
- Top toolbar with search, notifications, and profile actions
- Breadcrumbs derived from the active page
- Offline theme CSS via `AdminTheme(DefaultTheme)` + Theme Compiler

Pages call `PanelLayout::shell($pageKey, $title, $body, $breadcrumbs)`.

## Fixtures

`app/Data/Fixtures.php` is the entire data layer. No database, ORM, auth, or sessions.

Datasets include companies, contacts, leads, opportunities, tasks, meetings, invoices, users, roles, KPI cards, chart series, calendar events, notifications, and activity feed rows.

Lookup helpers (`lead`, `company`, `contact`, `opportunity`) power detail pages. Presenters in `app/Support/Presenter.php` format currency and dates for display.

## Adding pages

```php
final class CompaniesPage implements PageContract
{
    public function build(): Widget
    {
        return PanelLayout::shell(
            'companies',
            'Companies',
            Page::make(
                SectionHeader::make()->title('Companies'),
                DataTable::make()->columns([...])->rows(Fixtures::companies()),
            ),
            [['label' => 'Companies']],
        );
    }
}
```

Register in `routes/Web.php`, then smoke-check with `composer smoke`.

## Adding widgets

Compose existing widgets only. Prefer:

| Need | Widget |
|------|--------|
| KPI | `MetricCard` |
| Tables | `DataTable`, `StatusBadge`, `UserChip`, `Avatar` |
| Forms | `FormBuilder` + field widgets |
| Charts | `LineChart`, `BarChart`, `DonutChart` |
| Calendar | `Calendar` |
| Chrome | `Toolbar`, `SearchToolbar`, `Breadcrumb`, `EmptyState` |
| Detail | `DescriptionList`, `Card`, `SectionHeader` |

Do not duplicate widgets inside the starter. If a capability is missing, document it in `docs/crm-framework-review.md` and only then consider a smallest additive API in a package.

## Theme customization

```php
Theme::set(new AdminTheme(new DefaultTheme()));
```

CSS is compiled offline:

```bash
composer build
# writes public/assets/serixa.css via StyleSheet::compileToFile()
```

No Tailwind CDN. Runtime JS is synced from packages (`composer sync-runtime`). Charts and Calendar inject their own runtimes through `DocumentFactory`.

## Deployment

1. `composer install --no-dev`
2. `composer build` and `composer sync-runtime`
3. Point the web root at `public/`
4. Ensure `public/assets/serixa.css`, `serixa.js`, `charts.js`, and `calendar.js` are deployed
5. Serve with any PHP 8.2+ host (`php -S`, nginx+php-fpm, etc.)

This starter is a demo application: fixtures are in-memory and reset on every request.

## QA

```bash
composer qa      # phpunit + phpstan + cs-check
composer smoke   # render every route + unknown → 404
```

## Related docs

- [CRM framework review](crm-framework-review.md) — friction discovered during Product Validation
- Starter README: `starters/crm/README.md`
