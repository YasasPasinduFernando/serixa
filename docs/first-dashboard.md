# Build an admin dashboard shell

A focused walkthrough for an admin-style layout: a sidebar, a top bar, a row of KPI stat cards, and a data table sketch. Read [getting-started.md](getting-started.md) first if you haven't used SERIXA yet, and [first-blog.md](first-blog.md) if you want the fuller step-by-step tutorial style.

This assumes ~20 minutes and public APIs only.

## What you'll have at the end

- An app shell with `Scaffold` + `AppBar` + `Sidebar` + `MainContent`
- A row of `StatCard` widgets
- A `DataTable` sketch with header/rows and an empty state

## Step 1 — Create the project

There is **no dedicated "admin" CLI preset**. Use `blog`, `crm`, or `ecommerce` as a base and build your own admin layout on top — the presets only affect which starter pages get generated, not the widgets available to you.

```bash
serixa new admin --type=blog
cd admin
composer install
php -S localhost:8000 -t public
```

## Step 2 — Build the admin layout

Add a dedicated layout for the admin shell:

```bash
serixa make:layout Admin
```

Replace the generated `layouts/AdminLayout.php`:

```php
<?php

declare(strict_types=1);

namespace App\Layouts;

use Serixa\Component\AppBar;
use Serixa\Component\Link;
use Serixa\Component\MainContent;
use Serixa\Component\Padding;
use Serixa\Component\Scaffold;
use Serixa\Component\Sidebar;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Assets\StyleSheet;
use Serixa\Runtime\ClientRuntime;

final class AdminLayout
{
    private const NAV = [
        'dashboard' => 'Dashboard',
        'users' => 'Users',
        'settings' => 'Settings',
    ];

    public static function wrap(Document $document): Document
    {
        StyleSheet::register($document->assets());
        ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');

        return $document;
    }

    public static function shell(string $active, WidgetInterface $content): WidgetInterface
    {
        $links = [];
        $links[] = Text::make('Admin')->as('h2')->size('sm')->weight('semibold')->tone('muted');

        foreach (self::NAV as $page => $label) {
            $links[] = Link::make($label)
                ->href('/?page=' . $page)
                ->active($page === $active);
        }

        return Scaffold::make()
            ->appBar(
                AppBar::make()->title('Admin'),
            )
            ->sidebar(
                Sidebar::make(...$links)->ariaLabel('Admin navigation'),
            )
            ->body(
                MainContent::make(
                    Padding::make($content)->all(6),
                ),
            );
    }
}
```

Notes:

- `Scaffold::sidebar()` renders an `<aside>`; `Scaffold::body()` (alias: `Scaffold::content()`) renders the main region. `Scaffold` owns the layout — it doesn't decide what's "active" or fetch data.
- `MainContent::make(...)` wraps the page body with sensible flex/gap defaults, so you don't need to repeat spacing classes on every page.
- `Padding::make($content)->all(6)` is the fluent spacing builder. `Padding::make()->sm()`, `->md()`, `->lg()` are shortcuts for common insets (`p-4`, `p-6`, `p-8`).

## Step 3 — StatCard row

Build a small dashboard page:

```bash
serixa make:page Dashboard
```

Replace `pages/DashboardPage.php`:

```php
<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Component\Row;
use Serixa\Component\StatCard;
use Serixa\Document\Document;

final class DashboardPage
{
    public static function document(): Document
    {
        $stats = Row::make([
            StatCard::make()->title('Users')->value('1,284')->trend('+3.1%')->color('primary'),
            StatCard::make()->title('Sessions')->value('9,441')->trend('+12%')->color('success'),
            StatCard::make()->title('Errors')->value('12')->subtitle('Last 24h')->color('warning'),
            StatCard::make()->title('Uptime')->value('99.98%')->color('default'),
        ])->gap(4)->spaceBetween();

        $content = Column::make([
            PageHeader::make()->title('Dashboard')->subtitle('Workspace overview for the last 7 days.'),
            $stats,
        ])->gap(4)->alignStart();

        return Document::make()
            ->title('Dashboard · Admin')
            ->body(AdminLayout::shell('dashboard', $content));
    }
}
```

`StatCard` is for compact KPI tiles (`title` + `value` + optional `subtitle`/`trend`/`icon`/`color`). If you need a single inline label/value pair instead of a full card, use `Metric` — the two widgets overlap on purpose: `StatCard` for dashboard grids, `Metric` for inline stats inside other content.

## Step 4 — DataTable sketch

Add a Users page with a table:

```bash
serixa make:page Users
```

Replace `pages/UsersPage.php`:

```php
<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AdminLayout;
use Serixa\Component\Badge;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\DataTable;
use Serixa\Component\EmptyState;
use Serixa\Component\Link;
use Serixa\Component\PageHeader;
use Serixa\Document\Document;

final class UsersPage
{
    public static function document(): Document
    {
        $table = DataTable::make()
            ->caption('Users')
            ->headers(['Name', 'Email', 'Role', 'Actions'])
            ->rows([
                ['Ada Lovelace', 'ada@example.com', Badge::make('Admin')->color('primary'), Link::make('Edit')->href('/?page=users&edit=1')],
                ['Grace Hopper', 'grace@example.com', Badge::make('Editor')->color('neutral'), Link::make('Edit')->href('/?page=users&edit=2')],
            ])
            ->striped()
            ->hover()
            ->emptyState(
                EmptyState::make()
                    ->title('No users yet')
                    ->description('Invite your first teammate to get started.')
                    ->action(Button::make('Invite user')->primary()),
            );

        $content = Column::make([
            PageHeader::make()
                ->title('Users')
                ->subtitle('Everyone with access to this workspace.')
                ->actions(Button::make('Invite user')->primary()),
            $table,
        ])->gap(4)->alignStart();

        return Document::make()
            ->title('Users · Admin')
            ->body(AdminLayout::shell('users', $content));
    }
}
```

`DataTable` has two APIs:

- **Convenience** (used above): `->headers([...])->rows([...])`. Cells can be plain strings or any widget (`Badge`, `Link`, `Text`, …).
- **Composition**: build your own `Table::make()->head(...)->body(...)` and pass it to `DataTable::make()->child($table)` when you need full control over rows/cells. `->striped()`, `->hover()`, `->compact()` still apply in both modes.

Pass `->emptyState(EmptyState::make()->...)` and it renders automatically when `rows()` is empty — you don't need an `if` around it.

> SERIXA has no chart or calendar widgets yet. For analytics placeholders, use `EmptyState` (explicit "not built yet" messaging) or `Skeleton` (loading-style placeholder) rather than inventing a chart component.

## Step 5 — Wire routing

Same pattern as the blog tutorial — extend the `match()` in `public/index.php`:

```php
<?php

declare(strict_types=1);

use App\Layouts\AdminLayout;
use App\Pages\DashboardPage;
use App\Pages\UsersPage;
use Serixa\Rendering\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$page = strtolower((string) ($_GET['page'] ?? 'dashboard'));

$document = match ($page) {
    'dashboard' => DashboardPage::document(),
    'users' => UsersPage::document(),
    default => DashboardPage::document(),
};

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render(AdminLayout::wrap($document));
```

## Next steps

- Add interactive widgets (`Modal`, `Dropdown`, `Tabs`, `Toast`) — see [interactive-components.md](interactive-components.md).
- Full widget reference: [data-display.md](data-display.md), [ui-foundation.md](ui-foundation.md).
- Put this behind a real web server: [deployment.md](deployment.md).
- Add auth/sessions yourself — SERIXA does not provide either; see [faq.md](faq.md).
