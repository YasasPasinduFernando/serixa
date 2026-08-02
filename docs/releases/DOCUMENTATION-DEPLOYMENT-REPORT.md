# Documentation Website — Pre-Deploy Report

**Date:** 2026-08-02  
**Local QA:** Passed  
**Live URL:** https://serixa.yasaboy.com — **not healthy yet** (was HTTP 500; deploy still required)

## Fixes landed (local)

| Item | Status |
|------|--------|
| Open Graph / Twitter / JSON-LD / canonical | `DocumentFactory` |
| `robots.txt` | `website/public/robots.txt` |
| `sitemap.xml` | `website/public/sitemap.xml` |
| Favicon | `website/public/favicon.svg` |
| Search placeholder | Labeled **Search · Coming Soon** |
| Package catalog | Tiered Core / UI / DevTools / Official / Experimental |
| Apache rewrite + security headers | `website/public/.htaccess` |
| Deploy runbook | `website/DEPLOY.md` |
| Footer docs link | SiteLayout |

## Local verification

- PHPUnit: **38 tests, 274 assertions** OK  
- PHPStan: **OK**  
- HTTP 200: `/`, `/?page=packages`, `/robots.txt`, `/sitemap.xml`, `/favicon.svg`  
- Packages page includes Experimental tier + `serixa/stripe`  
- `og:title` present on home

## Live deploy blocker

The docs app is **PHP SSR**. Cloudflare Pages static hosting alone is insufficient unless a PHP origin (or static export) is configured.

**Required next step (Phase 6):** Point `serixa.yasaboy.com` at a PHP 8.2+ origin with document root `website/public`, run `composer install` + `composer build`, Cloudflare Full (strict). See `website/DEPLOY.md`.

Do **not** mark documentation publicly launched until `curl -sI https://serixa.yasaboy.com` returns **200**.
