# SERIXA Admin

`serixa/admin` is the official admin UI kit for SERIXA applications: server-rendered dashboard shells, tables and
cards built from `serixa/framework` widgets. It is deliberately **UI only**:

- **No routing** — `href()` strings are plain, you own the router.
- **No authentication** — `UserMenu`'s "sign out" link is just an `<a>`; wire it to your own logout route.
- **No ORM** — widgets take plain PHP values (strings, arrays, other widgets); load data however you like.
- **No JavaScript** — collapsible sidebars, the user menu and column-visibility dropdowns all use native
  `<details>`/`<summary>` disclosure. Everything renders to a plain HTML string via `render()`.

## Why a package?

Dashboards, tables and admin shells are common enough to standardize, but opinionated enough that they shouldn't
live in `serixa/framework` itself. Shipping them separately keeps the framework core lean while giving every
SERIXA app a consistent, batteries-included admin surface.

## Install (monorepo)

```bash
cd packages/admin
composer install
```

`composer.json` declares two Composer **path repositories** (`../../framework`, `../icons`), so no Packagist
access is required inside this repo.

## Architecture

Every widget:

- Lives under the `Serixa\Admin\` namespace.
- Extends `Serixa\Component\Component` (the same base class framework widgets use).
- Is `final`, built via a static `make()` factory, and exposes a fluent setter API.
- Implements `Serixa\Admin\Contracts\AdminWidgetInterface` (a marker extending the framework's `WidgetInterface`).
- Renders to a plain HTML string via `render()` — pass it to `Serixa\Rendering\Renderer` or `echo` it directly.

Composition over inheritance: `AppShell` composes the framework's `Scaffold`; `EmptyState` wraps the framework's
`EmptyState`; icons are always `Serixa\Icons\Component\Icon` (never inline SVG paths).

## Quick start

```php
require 'vendor/autoload.php';

use Serixa\Admin\Layout\AdminLayout;
use Serixa\Admin\Layout\FooterBar;
use Serixa\Admin\Layout\MainContent;
use Serixa\Admin\Layout\PageContainer;
use Serixa\Admin\Layout\Sidebar;
use Serixa\Admin\Layout\SidebarGroup;
use Serixa\Admin\Layout\SidebarItem;
use Serixa\Admin\Layout\TopBar;
use Serixa\Admin\Page\Page;
use Serixa\Admin\UserMenu\UserMenu;
use Serixa\Assets\StyleSheet;
use Serixa\Document\Document;
use Serixa\Rendering\Renderer;

$shell = AdminLayout::shell()
    ->topBar(
        TopBar::make()
            ->brand('Acme Admin')
            ->actions(UserMenu::make('Ada Lovelace')->item('Profile', '/admin/profile')->item('Sign out', '/logout', danger: true)),
    )
    ->sidebar(
        Sidebar::make()->children(
            SidebarGroup::make()->label('Overview')->items(
                SidebarItem::make('Dashboard')->icon('home')->href('/admin')->active(),
                SidebarItem::make('Users')->icon('users')->href('/admin/users'),
            ),
        ),
    )
    ->content(
        MainContent::make(
            PageContainer::make(
                Page::make('Welcome back.')->title('Dashboard')->description('Overview of your workspace'),
            ),
        ),
    )
    ->footer(FooterBar::make('© 2026 Acme Inc.'));

$document = Document::make()->title('Dashboard')->body($shell);
StyleSheet::register($document->assets());

echo (new Renderer())->render($document);
```

See `examples/dashboard.php`, `examples/users.php`, `examples/settings.php` and `examples/reports.php` for
complete, runnable pages.

## Widget catalog

### Layout (`Serixa\Admin\Layout\`)

| Widget | Purpose |
|--------|---------|
| `AppShell` | Composes the framework `Scaffold` into `sidebar()` / `topBar()` / `content()` / `footer()` slots. |
| `AdminLayout` | Thin, readable convenience wrapper around `AppShell` (`AdminLayout::shell()`). |
| `Sidebar` | `<nav aria-label="Primary">` landmark; holds `SidebarGroup`/`SidebarItem` children. |
| `SidebarGroup` | Labeled group of `SidebarItem`s, optionally `collapsible()` via native `<details>`. |
| `SidebarItem` | A nav entry: `icon()`, `href()`, `active()`, `badge()`, and optional nested `children()`. |
| `TopBar` | Brand/title, optional `search()` slot, trailing `actions()`. |
| `FooterBar` | Footer content (copyright, links). |
| `MainContent` | Padded content region wrapping page bodies. |
| `PageContainer` | Centered, `maxWidth()`-constrained container (`sm`…`2xl`, `full`). |

### Page (`Serixa\Admin\Page\`)

| Widget | Purpose |
|--------|---------|
| `Page` | Top-level composer: `title()` / `description()` / `breadcrumb()` / `actions()` build a lazy `PageHeader`, plus a `body()` of widgets. |
| `PageHeader` | Breadcrumb slot, title, description and trailing actions. |
| `PageTitle` | `<h1>`. |
| `PageDescription` | Muted `<p>`. |
| `PageActions` | Plain row wrapper for action widgets (use `Toolbar` instead if you need `role="toolbar"`). |

### Breadcrumb (`Serixa\Admin\Breadcrumb\`)

`Breadcrumb::make(BreadcrumbItem::make('Users')->href('/admin/users'), BreadcrumbItem::make('Edit')->current())`
renders a `<nav aria-label="Breadcrumb">` landmark with an ordered list; the current crumb gets
`aria-current="page"` and no link.

### Toolbar (`Serixa\Admin\Toolbar\`)

| Widget | Purpose |
|--------|---------|
| `Toolbar` | `role="toolbar"` container for grouped actions. |
| `ToolbarGroup` | Groups related `ToolbarAction`s inside a `Toolbar`. |
| `ToolbarAction` | Button-like action with optional `icon()`; renders `<a>` when `href()` is set, else `<button type="button">`. Variants: `primary()`, `danger()`, `ghost()`. |

### Cards (`Serixa\Admin\Cards\`)

| Widget | Purpose |
|--------|---------|
| `MetricCard` | Dashboard KPI: `title()`, `value()`, `trend($text, 'up'\|'down'\|'neutral')`, `icon()`, `color()`. |
| `MetricGrid` | Responsive grid of `MetricCard`s. |
| `InfoCard` | Generic titled card wrapping arbitrary body widgets. |
| `Statistic` | Compact inline label/value pair. |

### Components (`Serixa\Admin\Components\`)

| Widget | Purpose |
|--------|---------|
| `StatusBadge` | Semantic pill: `status('neutral'\|'info'\|'success'\|'warning'\|'danger')`. |
| `AvatarGroup` | Overlapping `Avatar` stack with a `max()` limit and "+N" overflow badge. |
| `UserChip` | `Avatar` + name (+ optional `subtitle()`), e.g. for table cells. |
| `DescriptionList` / `DescriptionItem` | `<dl>`/`<dt>`/`<dd>` key–value pairs. |

### UserMenu (`Serixa\Admin\UserMenu\`)

`UserMenu::make('Ada Lovelace')->item('Profile', '/admin/profile')->divider()->item('Sign out', '/logout', danger: true)`
— an avatar + name trigger disclosed via native `<details>`/`<summary>`. Items are plain links; there is no
authentication logic, only markup.

### EmptyState (`Serixa\Admin\EmptyState\`)

Admin-styled wrapper around the framework's `EmptyState`, defaulting its icon slot to `Icon::make(...)->size('2xl')`.

### Tables (`Serixa\Admin\Tables\`)

| Widget | Purpose |
|--------|---------|
| `TableToolbar` | Search + actions row above a table. |
| `SearchToolbar` | Search input row, defaulting to the framework `SearchField`. |
| `TableFilters` | Wrapping row for filter fields (`Select`, etc.) placed above a table. |
| `BulkActions` | Contextual bar with a `summary()` and bulk-operation buttons. |
| `RowActions` | Compact, right-aligned row of action links for a table row. |
| `ColumnVisibility` | SSR-only "toggle columns" `<details>` disclosure (checkboxes are static — no client-side toggling). |
| `ExportButton` / `ImportButton` | `ToolbarAction` presets with export/import icons. |

Combine these with the framework's `DataTable` for full table pages — see `examples/users.php` and
`examples/reports.php`.

## Theming

`Serixa\Admin\Theme\AdminTheme` implements `Serixa\Contracts\ThemeInterface` and **decorates** an inner theme:

```php
final class AdminTheme implements ThemeInterface
{
    public function __construct(private readonly ThemeInterface $inner) {}

    public function classes(string $component, array $state = []): array
    {
        if (str_starts_with($component, 'admin.')) {
            return AdminThemeMap::classes($component, $state, $this->inner);
        }

        return $this->inner->classes($component, $state);
    }

    // token(), buttonPrimary(), buttonSecondary(), buttonDanger(), buttonGhost()
    // all delegate straight to $this->inner.
}
```

- Component names starting with `admin.` (e.g. `admin.sidebar.item`, `admin.metricCard`) are resolved by the
  internal `AdminThemeMap`, which borrows color/radius/shadow/typography tokens from the inner theme where
  possible so admin widgets stay on-brand.
- Every other component name (`button`, `avatar`, `text`, …) is delegated untouched to the inner theme, so
  `serixa/admin` sits on top of `DefaultTheme` or any custom `ThemeInterface` your app already uses.

You never construct `AdminTheme` by hand inside a widget. Every admin widget uses the
`Serixa\Admin\Support\ResolvesAdminTheme` trait:

```php
trait ResolvesAdminTheme
{
    protected function theme(): AdminTheme
    {
        $current = ThemeManager::current();

        return $current instanceof AdminTheme ? $current : new AdminTheme($current);
    }
}
```

This means `serixa/admin` works out of the box with zero setup — if the host app hasn't configured
`AdminTheme` explicitly, each widget wraps whatever theme `ThemeManager::current()` returns. If you want to
configure it once at bootstrap (recommended, since it's constructed once instead of per-render):

```php
use Serixa\Admin\Theme\AdminTheme;
use Serixa\Theme\DefaultTheme;
use Serixa\Theme\ThemeManager;

ThemeManager::set(new AdminTheme(new DefaultTheme()));
```

## Icons

All icons go through `Serixa\Icons\Component\Icon::make($name)` — the admin package never inlines SVG paths.
Any pack registered with `serixa/icons` (Heroicons by default, plus Feather and Lucide) works automatically;
see [`serixa/icons` docs](../../icons/docs/icons.md) for the full icon list and custom pack registration.

## Exceptions

`Serixa\Admin\Exceptions\AdminException` (extends the framework's `InvalidArgumentException`) is thrown by
admin-specific option validation — e.g. an unknown `StatusBadge::status()` or `MetricCard::color()` value —
via the internal `Serixa\Admin\Support\AssertsAdminOption` trait.

## Testing

```bash
composer test      # PHPUnit 11
composer phpstan    # level 6
composer cs-check    # PHP-CS-Fixer, dry-run
composer qa          # all three
```

`tests/AdminTest.php` resets `ThemeManager` and `IconRegistry` in `setUp()` so each test starts from a clean
theme/icon-pack state, then exercises shell composition, active/current-page ARIA attributes, landmark roles,
theme class resolution, and exception handling.
