# Document & Asset Engine (Milestone 5)

Status: **complete** (`v0.5.0`).

SERIXA can now emit complete HTML documents — not only fragments.

## Why Document is separate from Renderer

| Concern | Owner |
|---------|-------|
| Widget tree → HTML fragment | `Renderer` + pipeline |
| `html` / `head` / `body`, SEO, assets | `Document` + `DocumentRenderer` |

Keeping them apart means dashboards, emails, and partial HTMX responses stay lightweight, while full pages get a proper shell.

## Why AssetManager exists

Assets are not widgets. CSS, JS, fonts, icons, preload/prefetch, versioning, and manifests need a registry that can later plug into Vite or CDN tooling without changing `Document::make()` APIs.

## Why SEO is a subsystem

Title, description, robots, Open Graph, Twitter Cards, and JSON-LD are cross-cutting. `SeoManager` generates head elements so Document stays a fluent façade and metadata stays unit-testable.

## SSR preparation

SSR needs a full HTML string (doctype → `</html>`). `DocumentRenderer` is that surface: SEO + assets + body fragments. Future streaming can wrap the same composition.

## Backward compatibility

```php
(new Renderer())->render(Button::make('OK')); // fragment — unchanged
(new Renderer())->render(Document::make()->title('X')->body(...)); // full page
```

`RendererInterface::render()` accepts `DocumentInterface|WidgetInterface|string` (additive union).

## API

```php
Document::make()
    ->title('Dashboard')
    ->description('SERIXA Admin')
    ->keywords(['dashboard', 'admin'])
    ->themeColor('#2563eb')
    ->language('en')
    ->body(
        Center::make(
            Column::make([
                Text::make('Hello'),
                Button::make('Continue')->primary(),
            ]),
        ),
    );
```

### Head elements

`Title`, `Meta`, `Link`, `Script`, `Style` — plus Head helpers for charset, viewport, favicon, canonical, OG, Twitter.

### Assets

```php
$document->assets()
    ->css('/app.css')
    ->js('/app.js', ['defer' => true])
    ->bundle((new AssetBundle('vendor'))->css('/vendor.css'))
    ->preload('/hero.webp', 'image');
```

`AssetManifest` + `AssetResolver` map logical paths → versioned public URLs (no Vite implementation).

## Classes

| Class | Role |
|-------|------|
| `Document` | Fluent page builder |
| `Html`, `Head`, `Body` | Document structure |
| `SeoManager` | Metadata → head tags |
| `AssetManager` / `Bundle` / `Manifest` / `Resolver` | Asset architecture |
| `DocumentRenderer` | Full page composition |

## Non-goals

Routing, forms, HTTP, Vite bundling, authentication, ORM, CLI.
