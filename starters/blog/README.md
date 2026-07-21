# SERIXA Blog Starter

The official SERIXA Blog Starter — a production-ready-looking blog CMS
**scaffold**, built entirely from [`serixa/framework`](../../framework),
[`serixa/admin`](../../packages/admin) and [`serixa/icons`](../../packages/icons)
widgets.

> **Fake data disclaimer.** There is no database, ORM, authentication, or
> real CRUD in this starter. Every page renders from static PHP arrays in
> `App\Data\Fixtures`. Forms post to `#` and do not persist anything. This is
> intentional — the starter exists to demonstrate composing a realistic admin
> UI purely from SERIXA widgets, ready for you to wire up to your own data
> layer.

## Requirements

- PHP 8.2+
- Composer

## Installation

```bash
cd starters/blog
composer install
composer start
# → http://localhost:8000
```

`composer start` runs `php -S localhost:8000 -t public`. The three SERIXA
packages are resolved via local Composer **path repositories**
(`../../framework`, `../../packages/icons`, `../../packages/admin`) —
nothing is downloaded from Packagist.

If you ever upgrade the framework and need to refresh the copy of its
client runtime script served from `public/assets/runtime/serixa.js`, run:

```bash
php bin/sync-runtime.php
```

## Structure

```
starters/blog/
  app/
    Data/Fixtures.php      Static fake data: posts, categories, tags, users, comments, media
    Support/Presenter.php  Small status → StatusBadge mapping helpers
    DocumentFactory.php    Builds every Document: title, SEO, Tailwind CDN, client runtime
  layouts/
    BlogLayout.php         Shared shell: sidebar, top bar, footer (Serixa\Admin\Layout\AdminLayout)
  pages/
    DashboardPage.php      Metrics + recent posts
    PostsPage.php          Post index: search, filters, bulk actions, row actions
    CreatePostPage.php     New post form
    EditPostPage.php       Edit post form, prefilled by ?id=
    CategoriesPage.php     Category index
    TagsPage.php           Tag chip cloud + table
    MediaPage.php          Media library grid
    CommentsPage.php       Comment moderation queue
    UsersPage.php          User index
    ProfilePage.php        Signed-in user's own account page
    SettingsPage.php       Site-wide preferences form
    NotFoundPage.php       404 page
  routes/
    Web.php                `?page=` → page class lookup, used by public/index.php
  public/
    index.php              Front controller
    app.css                A handful of tweaks the Tailwind CDN build can't express
    assets/runtime/serixa.js  Copy of the framework's progressive-enhancement runtime
  config/app.php           Plain config array (name, tagline, timezone, url)
  bin/sync-runtime.php     Re-copies serixa.js from the framework package
  storage/                 Empty — reserved for a future file-based cache, if you add one
  tests/SmokeTest.php      Renders every page and asserts on shell + page markers
```

## How routing works

`public/index.php` reads `?page=` (defaulting to `dashboard`), asks
`App\Web::isKnown()` whether it is a recognized route, sends a real
`404` status code if not, and renders whatever `App\Web::document()`
returns:

```php
$page = $_GET['page'] ?? 'dashboard';

if (! Web::isKnown($page)) {
    http_response_code(404);
}

$document = Web::document($page, $_GET);
echo (new Renderer())->render($document);
```

Every page class exposes a single static `document(array $query = []): Document`
method, so pages are trivially testable without a running web server (see
`tests/SmokeTest.php`).

## Adding a page

1. Add a fixture accessor to `App\Data\Fixtures` if you need new fake data.
2. Create `pages/YourPage.php` with `namespace App;` and a
   `public static function document(array $query = []): Document` method
   that builds a `Serixa\Admin\Page\Page` and wraps it with
   `BlogLayout::wrap('your-page-key', $page)`.
3. Register the route in `routes/Web.php`'s `ROUTES` map.
4. Add a `SidebarItem` in `layouts/BlogLayout.php::sidebar()` if the page
   should be reachable from the navigation, and add a title to
   `BlogLayout::TITLES`.

## Adding a widget

This starter intentionally has **no custom admin widgets** — everything is
composed from:

- `Serixa\Component\*` (the framework: `Form`, `Input`, `Select`, `DataTable`, `Row`, `Column`, …)
- `Serixa\Admin\*` (the admin kit: `Page`, `AdminLayout`, `DataTable` chrome, `MetricCard`, `StatusBadge`, …)
- `Serixa\Icons\Component\Icon` (inline SVG icons)

If you find yourself repeating the same composition across pages, extract a
small static helper (see `App\Support\Presenter`) rather than duplicating an
existing `serixa/admin` widget. Custom, reusable app-level widgets can live
under `components/` and should implement `Serixa\Contracts\WidgetInterface`.

## Customization

- **Branding**: `App\BlogLayout::topBar()` sets the brand text and the
  signed-in user menu.
- **Navigation**: `App\BlogLayout::sidebar()` builds the `SidebarGroup`/`SidebarItem` tree.
- **SEO/assets**: `App\DocumentFactory::make()` sets the page title suffix,
  meta description, theme color, and registers the Tailwind CDN build plus
  the client runtime script.
- **Styling**: Tailwind utility classes come from the CDN build; anything
  that cannot be expressed with utilities lives in `public/app.css`.

## Testing & QA

```bash
composer test      # PHPUnit
composer phpstan    # Static analysis (level 6)
composer cs-check   # Coding standard (dry run)
composer cs-fix     # Coding standard (auto-fix)
composer qa         # test + phpstan + cs-check
```

## Deployment

This starter has no build step — it is plain PHP rendering server-side
HTML. Point any PHP 8.2+ web server (nginx + PHP-FPM, Apache, etc.) at the
`public/` directory as the document root, run `composer install --no-dev
--optimize-autoloader` for production, and serve. Because there is no
database, there is nothing to migrate or provision beyond PHP itself.

## Packages used

| Package | Role |
|---|---|
| [`serixa/framework`](../../framework) | Core widgets, rendering pipeline, theming, forms |
| [`serixa/admin`](../../packages/admin) | Admin shell, page/table/toolbar chrome, cards |
| [`serixa/icons`](../../packages/icons) | Inline SVG icon widget |
