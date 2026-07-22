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
composer sync-runtime
```

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
  public/        index.php bootstrap + app.css
```

Dark mode is cookie/query driven (`?theme=dark|light`) and applied via `data-theme` on the site shell.
