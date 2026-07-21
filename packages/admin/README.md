# SERIXA Admin

Official admin UI kit for the SERIXA ecosystem (`serixa/admin`).

Server-rendered shells, tables, and dashboard widgets · no routing · no auth · no ORM · no JavaScript.

## Install (monorepo)

```bash
cd packages/admin
composer install
```

Path repositories point at `../../framework` and `../icons`.

## Quick start

```php
use Serixa\Admin\Layout\AdminLayout;
use Serixa\Admin\Layout\FooterBar;
use Serixa\Admin\Layout\MainContent;
use Serixa\Admin\Layout\Sidebar;
use Serixa\Admin\Layout\SidebarItem;
use Serixa\Admin\Layout\TopBar;
use Serixa\Admin\Page\Page;
use Serixa\Rendering\Renderer;

$shell = AdminLayout::shell()
    ->topBar(TopBar::make()->brand('Acme Admin'))
    ->sidebar(Sidebar::make()->children(
        SidebarItem::make('Dashboard')->icon('home')->href('/admin')->active(),
    ))
    ->content(MainContent::make(Page::make('Hello, admin.')->title('Dashboard')))
    ->footer(FooterBar::make('© 2026 Acme Inc.'));

echo (new Renderer())->render($shell);
```

## What's inside

- **Layout** — `AppShell`, `AdminLayout`, `Sidebar`, `SidebarGroup`, `SidebarItem`, `TopBar`, `FooterBar`, `MainContent`, `PageContainer`
- **Page** — `Page`, `PageHeader`, `PageTitle`, `PageDescription`, `PageActions`
- **Breadcrumb** — `Breadcrumb`, `BreadcrumbItem`
- **Toolbar** — `Toolbar`, `ToolbarGroup`, `ToolbarAction`
- **Cards** — `MetricCard`, `MetricGrid`, `InfoCard`, `Statistic`
- **Components** — `StatusBadge`, `AvatarGroup`, `UserChip`, `DescriptionList`, `DescriptionItem`
- **UserMenu** — `UserMenu`
- **EmptyState** — `EmptyState`
- **Tables** — `TableToolbar`, `SearchToolbar`, `TableFilters`, `BulkActions`, `RowActions`, `ColumnVisibility`, `ExportButton`, `ImportButton`

All widgets are `final`, built with static `make()` factories and a fluent API, extend `Serixa\Component\Component`,
and `render()` to plain HTML strings — same conventions as `serixa/framework` and `serixa/icons`.

## Theming

`Serixa\Admin\Theme\AdminTheme` decorates any `Serixa\Contracts\ThemeInterface`. Component names starting with
`admin.` are resolved by the internal `AdminThemeMap` (Tailwind classes, borrowing color/radius/shadow tokens from
the wrapped theme); every other component name is delegated untouched. You never need to construct `AdminTheme`
yourself — every admin widget uses the `ResolvesAdminTheme` trait, which wraps `ThemeManager::current()`
automatically if it isn't already an `AdminTheme`.

```php
use Serixa\Admin\Theme\AdminTheme;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

ThemeManager::set(new AdminTheme(new DefaultTheme()));
```

## Icons

All icons go through `Serixa\Icons\Component\Icon` — never inline SVG. Install any icon pack supported by
`serixa/icons` (Heroicons, Feather, Lucide) and admin widgets pick it up automatically.

## Docs

See [docs/admin.md](docs/admin.md).

## Examples

```bash
php examples/dashboard.php > dashboard.html
php examples/users.php > users.html
php examples/settings.php > settings.html
php examples/reports.php > reports.html
```

Each renders a full HTML page (Tailwind via CDN) — open the output in a browser.

## Quality

```bash
composer test
composer phpstan
composer cs-check
composer qa
```

## License

MIT
