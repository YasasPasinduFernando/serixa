# Build a blog in 30 minutes

A complete, copy-paste tutorial for a small blog: home page, an articles list, an about page, and a contact form. Everything here uses the SERIXA **CLI** and **public APIs only** — no framework source changes.

Time budget: ~30 minutes if you copy-paste each step.

## What you'll have at the end

- A running PHP app served with `php -S`
- A shared layout (`Scaffold` + `AppBar` + nav + footer)
- Home, Articles, About, and Contact pages
- A `Card`-based article list
- A contact `Form` with labeled fields
- Your own tiny router in `public/index.php` (SERIXA does not ship a router)

## Before you start

- PHP 8.2+ and Composer installed
- SERIXA cloned or installed somewhere on disk

Two ways to get the CLI:

```bash
# Inside a clone of the framework
php bin/serixa

# Or, once installed as a dependency
vendor/bin/serixa
```

> **Important:** generate your app **outside** the SERIXA framework clone (a sibling folder), not inside it. If your app's `composer.json` uses a `path` repository pointing at a *parent* directory, Composer can recurse into nested `vendor` folders and hang. See [Path-repository recursion](troubleshooting.md#path-repository-recursion).

---

## Step 1 — Create the project (2 min)

```bash
serixa new blog
```

This scaffolds a `blog/` folder next to wherever you ran the command, with a `blog` preset:

```text
blog/
  public/
    index.php
    app.css
    assets/runtime/serixa.js
  pages/
    HomePage.php
    PostsPage.php
  components/
  layouts/
    AppLayout.php
  config/app.php
  composer.json
```

`PostsPage.php` is a leftover preset stub — the blog preset generates it, but nothing routes to it yet. We'll build our own `ArticlesPage` instead and delete the stub at the end.

## Step 2 — Install and run (3 min)

```bash
cd blog
composer install
php -S localhost:8000 -t public
```

Open http://localhost:8000. You should see "Welcome to your SERIXA blog application." That's `HomePage.php` rendering through `AppLayout`.

Note the `-t public` flag: SERIXA apps always serve from `public/`, because that's where `index.php` and `assets/runtime/serixa.js` live.

## Step 3 — Look at what was generated (3 min)

`pages/HomePage.php`:

```php
<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AppLayout;
use Serixa\Component\Column;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class HomePage
{
    public static function document(): Document
    {
        $body = Column::make([
            AppLayout::titleBar('Blog · blog'),
            Text::make('Welcome to your SERIXA blog application.')
                ->tone('muted'),
        ])->gap(3)->alignStart();

        return Document::make()
            ->title('Blog · blog')
            ->description('SERIXA blog starter')
            ->language('en')
            ->body($body);
    }
}
```

`layouts/AppLayout.php`:

```php
<?php

declare(strict_types=1);

namespace App\Layouts;

use Serixa\Component\Container;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;
use Serixa\Assets\StyleSheet;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

final class AppLayout
{
    public static function wrap(Document $document): Document
    {
        StyleSheet::register($document->assets());
        ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');

        return $document;
    }

    public static function titleBar(string $title): WidgetInterface
    {
        return Container::make(
            Text::make($title)->as('h1')->size('2xl')->weight('bold'),
        );
    }
}
```

`public/index.php`:

```php
<?php

declare(strict_types=1);

use App\Layouts\AppLayout;
use App\Pages\HomePage;
use Serixa\Rendering\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$page = strtolower((string) ($_GET['page'] ?? 'home'));

$document = match ($page) {
    default => HomePage::document(),
};

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render(AppLayout::wrap($document));
```

Three things to notice, since they shape everything below:

1. **Pages are plain PHP classes** with a static `document(): Document` method. No base class required.
2. **`AppLayout::wrap()`** registers assets (Tailwind CDN script + your CSS + the runtime JS) on the `Document` before rendering.
3. **Routing is a `match()` in `index.php`.** That's app code, not a framework feature — SERIXA has no router. You will extend this `match()` yourself as you add pages.

## Step 4 — Edit the home page (3 min)

Open `pages/HomePage.php` and replace the body:

```php
<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AppLayout;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class HomePage
{
    public static function document(): Document
    {
        $body = Column::make([
            Text::make('Ideas for building calmer software')
                ->as('h1')->size('2xl')->weight('bold'),
            Text::make('A small blog about PHP, product craft, and shipping things that work.')
                ->size('lg')->tone('muted'),
            Row::make([
                Button::make('Read the articles')->primary()->large(),
                Button::make('Get in touch')->secondary()->large(),
            ])->gap(3),
        ])->gap(4)->alignStart();

        return Document::make()
            ->title('My SERIXA Blog')
            ->description('A small blog built with SERIXA.')
            ->language('en')
            ->body($body);
    }
}
```

Refresh http://localhost:8000 — you should see the new heading, subtext, and two buttons. The buttons don't link anywhere yet; that's fixed once the layout has navigation.

## Step 5 — Build a real layout (Scaffold + AppBar + ClientRuntime) (5 min)

Right now `AppLayout::titleBar()` is just a `Container` — no navigation, no footer. Replace the whole file with a `Scaffold`-based shell:

```php
<?php

declare(strict_types=1);

namespace App\Layouts;

use Serixa\Component\AppBar;
use Serixa\Component\Container;
use Serixa\Component\Footer;
use Serixa\Component\Link;
use Serixa\Component\Logo;
use Serixa\Component\Scaffold;
use Serixa\Component\Text;
use Serixa\Contracts\WidgetInterface;
use Serixa\Document\Document;
use Serixa\Runtime\ClientRuntime;

final class AppLayout
{
    private const NAV = [
        'home' => 'Home',
        'articles' => 'Articles',
        'about' => 'About',
        'contact' => 'Contact',
    ];

    /**
     * Register CSS/JS on the Document. Call this once per request, right before render.
     */
    public static function wrap(Document $document): Document
    {
        StyleSheet::register($document->assets());
        ClientRuntime::register($document->assets(), '/assets/runtime/serixa.js');

        return $document;
    }

    /**
     * Wrap page content in the Scaffold shell (AppBar + nav + footer).
     */
    public static function shell(string $active, WidgetInterface $content): WidgetInterface
    {
        $links = [];

        foreach (self::NAV as $page => $label) {
            $links[] = Link::make($label)
                ->href('/?page=' . $page)
                ->active($page === $active);
        }

        $bar = AppBar::make(Logo::make()->text('My Blog')->href('/?page=home'))
            ->actions(...$links);

        $footer = Footer::make(
            Text::make('© 2026 My Blog.')->size('sm')->tone('muted'),
        );

        return Scaffold::make()
            ->appBar($bar)
            ->body(Container::make($content)->maxWidth('lg')->padding('lg'))
            ->footer($footer);
    }
}
```

Key points:

- `AppBar::make()` takes the leading content (here, a `Logo`) and `->actions(...)` for right-aligned items (here, nav links).
- `Scaffold::make()->appBar(...)->body(...)->footer(...)` is purely structural — it does not add auth, state, or routing.
- `Link::make($label)->active($page === $active)` is how you mark the current nav item. SERIXA does not track "current page" for you; your app passes `$active` in.

Now wire `HomePage` through the new shell. Update `pages/HomePage.php`:

```php
<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AppLayout;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\Row;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class HomePage
{
    public static function document(): Document
    {
        $content = Column::make([
            Text::make('Ideas for building calmer software')
                ->as('h1')->size('2xl')->weight('bold'),
            Text::make('A small blog about PHP, product craft, and shipping things that work.')
                ->size('lg')->tone('muted'),
            Row::make([
                Button::make('Read the articles')->primary()->large(),
                Button::make('Get in touch')->secondary()->large(),
            ])->gap(3),
        ])->gap(4)->alignStart();

        return Document::make()
            ->title('My SERIXA Blog')
            ->description('A small blog built with SERIXA.')
            ->language('en')
            ->body(AppLayout::shell('home', $content));
    }
}
```

Refresh the browser. You now have a top bar with your logo and nav links, page content in a centered container, and a footer.

## Step 6 — Add the Articles, About, and Contact pages (2 min)

Use the CLI's `make:page` command for each one:

```bash
serixa make:page Articles
serixa make:page About
serixa make:page Contact
```

This creates `pages/ArticlesPage.php`, `pages/AboutPage.php`, and `pages/ContactPage.php`, each with a generic placeholder body. We'll fill them in next.

> Run `make:page` again with the same name and it refuses to overwrite the file (`Refusing to overwrite existing file`). Rename the page, or delete the existing file first, if you need to regenerate it.

## Step 7 — Build the Articles page with a Card list (5 min)

Replace `pages/ArticlesPage.php`:

```php
<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AppLayout;
use Serixa\Component\Badge;
use Serixa\Component\Card;
use Serixa\Component\Column;
use Serixa\Component\Link;
use Serixa\Component\PageHeader;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class ArticlesPage
{
    /** @var list<array{title: string, excerpt: string, category: string}> */
    private const POSTS = [
        ['title' => 'Server-rendered interfaces that feel alive', 'excerpt' => 'How progressive enhancement keeps PHP apps fast without a client framework.', 'category' => 'Engineering'],
        ['title' => 'Design systems for small teams', 'excerpt' => 'A lightweight approach to shared language and reusable patterns.', 'category' => 'Design'],
        ['title' => 'Writing PHP you can read in a year', 'excerpt' => 'Small habits that keep a codebase readable as it grows.', 'category' => 'Engineering'],
    ];

    public static function document(): Document
    {
        $cards = [];

        foreach (self::POSTS as $post) {
            $cards[] = Card::make(
                Column::make([
                    Badge::make($post['category'])->color('primary'),
                    Text::make($post['title'])->as('h2')->size('xl')->weight('bold'),
                    Text::make($post['excerpt'])->tone('muted'),
                    Link::make('Read article')->href('/?page=articles'),
                ])->gap(2)->alignStart(),
            )->padding('lg');
        }

        $content = Column::make([
            PageHeader::make()->title('Articles')->subtitle('Everything we have published so far.'),
            Column::make($cards)->gap(4)->alignStart(),
        ])->gap(5);

        return Document::make()
            ->title('Articles · My SERIXA Blog')
            ->body(AppLayout::shell('articles', $content));
    }
}
```

This is a static list for the tutorial. In a real app you'd load posts from a database or flat files and map them the same way — SERIXA only cares that you hand it widgets, not where the data came from.

Visit http://localhost:8000/?page=articles once routing is wired in Step 9 — for now, note that `AppLayout::shell('articles', ...)` already highlights the "Articles" nav link.

## Step 8 — Build the About page (2 min)

Replace `pages/AboutPage.php`:

```php
<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AppLayout;
use Serixa\Component\Column;
use Serixa\Component\PageHeader;
use Serixa\Component\Text;
use Serixa\Document\Document;

final class AboutPage
{
    public static function document(): Document
    {
        $content = Column::make([
            PageHeader::make()->title('About')->subtitle('Why this blog exists.'),
            Text::make('This is a small blog built to try out SERIXA — a PHP UI framework that renders HTML on the server.')
                ->tone('muted'),
        ])->gap(4);

        return Document::make()
            ->title('About · My SERIXA Blog')
            ->body(AppLayout::shell('about', $content));
    }
}
```

## Step 9 — Build the Contact page with a Form (5 min)

Replace `pages/ContactPage.php`:

```php
<?php

declare(strict_types=1);

namespace App\Pages;

use App\Layouts\AppLayout;
use Serixa\Component\Alert;
use Serixa\Component\Button;
use Serixa\Component\Column;
use Serixa\Component\FieldGroup;
use Serixa\Component\Form;
use Serixa\Component\Input;
use Serixa\Component\Label;
use Serixa\Component\PageHeader;
use Serixa\Component\Textarea;
use Serixa\Document\Document;

final class ContactPage
{
    public static function document(): Document
    {
        $form = Form::make(
            Column::make([
                FieldGroup::make(
                    Label::make('Name')->for('contact-name'),
                    Input::make()->id('contact-name')->name('name')->autocomplete('name')->required(),
                ),
                FieldGroup::make(
                    Label::make('Email')->for('contact-email'),
                    Input::make()->id('contact-email')->name('email')->type('email')->autocomplete('email')->required(),
                ),
                FieldGroup::make(
                    Label::make('Message')->for('contact-message'),
                    Textarea::make()->id('contact-message')->name('message')->rows(6)->minLength(10)->required(),
                ),
                Button::make('Send message')->type('submit')->primary(),
            ])->gap(4),
        )->method('POST')->action('/?page=contact');

        $content = Column::make([
            PageHeader::make()->title('Contact')->subtitle('Send a note — we read every message.'),
            Alert::make()->tone('info')->title('Heads up')->body('This form renders HTML only. Wire your own handler to actually receive submissions.'),
            $form,
        ])->gap(5);

        return Document::make()
            ->title('Contact · My SERIXA Blog')
            ->body(AppLayout::shell('contact', $content));
    }
}
```

Two things worth calling out:

- **`Form` is render-only.** SERIXA emits a real `<form method="POST" action="...">`, but nothing in the framework parses `$_POST` or validates fields. That's entirely your app's job.
- **`FieldGroup` + `Label::for()` + `Input::id()`** is the pattern for accessible fields: the label's `for` matches the input's `id`.

## Step 10 — Wire your own routing (3 min)

SERIXA ships no router. `index.php` is a plain `match()` over `$_GET['page']` — extend the one that's already there. Replace `public/index.php`:

```php
<?php

declare(strict_types=1);

use App\Layouts\AppLayout;
use App\Pages\AboutPage;
use App\Pages\ArticlesPage;
use App\Pages\ContactPage;
use App\Pages\HomePage;
use Serixa\Rendering\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

$page = strtolower((string) ($_GET['page'] ?? 'home'));

$document = match ($page) {
    'home' => HomePage::document(),
    'articles' => ArticlesPage::document(),
    'about' => AboutPage::document(),
    'contact' => ContactPage::document(),
    default => HomePage::document(),
};

header('Content-Type: text/html; charset=utf-8');
echo (new Renderer())->render(AppLayout::wrap($document));
```

This is plain PHP — no framework hook, no config file, no annotations. If you outgrow `match()`, drop in any routing library you like (or write your own); SERIXA does not care how `$document` was chosen, only that you hand it a `Document`.

Now the nav links from Step 5 all resolve:

- http://localhost:8000/?page=home
- http://localhost:8000/?page=articles
- http://localhost:8000/?page=about
- http://localhost:8000/?page=contact

Click through the nav bar and confirm the active link highlights on each page.

## Step 11 — Clean up the leftover stub (1 min)

`pages/PostsPage.php` was generated by the `blog` preset but never wired into routing. Delete it:

```bash
rm pages/PostsPage.php
```

(On Windows PowerShell: `Remove-Item pages\PostsPage.php`.)

## You're done

You now have a small, real SERIXA app: a shared layout, four pages, a card list, and a contact form — built entirely from public APIs and the CLI.

## What's explicitly out of scope

Keep these framework boundaries in mind as you grow the app:

- **No router.** You just wrote one; that's expected, not a bug.
- **No auth or sessions.** A "Login" page would be HTML only until you add your own session logic.
- **No form validation/submission pipeline.** `Form` renders HTML; processing `$_POST` is your app's code.
- **No database layer.** Fetch your posts from wherever you like and map them to widgets.

## Next steps

- Add an admin-style page? See [first-dashboard.md](first-dashboard.md).
- Add a reusable `components/ArticleCard.php` with `serixa make:component ArticleCard` instead of building cards inline in `ArticlesPage`.
- Put this on a real server: [deployment.md](deployment.md).
- Something not working? [troubleshooting.md](troubleshooting.md).
