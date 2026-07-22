# SERIXA Documentation Website

Official documentation site for the SERIXA ecosystem.

Built as a **SERIXA application** inside this monorepo — same architecture as official starters. It consumes `serixa/framework` and official packages via Composer path repositories.

## Requirements

- PHP 8.2+
- Composer

## Install

```bash
cd website
composer install
composer build
```

CSS is compiled offline from `framework/assets/css/serixa.css` + `resources/css/app.css` into `public/assets/app.css`. No Tailwind CDN.

## Run

```bash
composer start
# → http://localhost:8080
```

## Routes

| Page | `?page=` |
|------|----------|
| Home | `home` (default) |
| Docs | `docs-{slug}` (see sidebar) |
| Packages | `packages` |
| Examples | `examples-blog`, `examples-admin`, `examples-crm`, `examples-ecommerce`, `examples-portfolio` |
| Playground | `playground` |

Documentation content is loaded from the monorepo [`docs/`](../docs/) folder (and `ROADMAP.md`).

## QA

```bash
composer qa
```

## Architecture

```text
website/
  app/           DocumentFactory, DocsCatalog, ThemeMode, Markdown
  components/    SiteNav, DocsSidebar, Breadcrumbs, DocPager, ProseHtml
  layouts/       SiteLayout, DocsLayout
  pages/         Home, Docs, Packages, Playground, Examples
  routes/        Web.php
  resources/css  App overrides
  public/        index.php + assets/app.css
```

Dark mode is cookie/query driven (`?theme=dark|light`) via `data-theme` / `.dark` on the site shell.

See [docs/assets.md](../docs/assets.md) for the official asset pipeline.
