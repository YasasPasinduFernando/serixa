# Deploying the SERIXA docs site

Production URL: **https://serixa.yasaboy.com**

The site is a **PHP SSR** SERIXA app (`website/`). Document root must be **`website/public`**.

## Why you may see HTTP 500

Common causes:

1. Document root pointed at `website/` instead of `website/public`
2. Missing `website/vendor` (`composer install` not run on the host)
3. PHP < 8.2
4. Path repositories cannot resolve `../framework` / `../packages/*` on the host
5. Missing compiled CSS (`composer build` not run)

## Recommended: PHP host behind Cloudflare

1. Deploy the monorepo (or a release checkout) to a PHP 8.2+ host.
2. Set the vhost / app root to `website/public`.
3. On the server:

```bash
cd website
composer install --no-dev --optimize-autoloader
composer build
```

4. Put Cloudflare in front of the origin (orange-cloud DNS for `serixa.yasaboy.com`).
5. SSL/TLS mode: **Full (strict)** once the origin has a valid cert (or Cloudflare Origin CA).
6. Enable Brotli / compression and caching for `/assets/*`.

Verify:

```bash
curl -sI https://serixa.yasaboy.com
# Expect: HTTP/2 200
curl -sI https://serixa.yasaboy.com/robots.txt
curl -sI https://serixa.yasaboy.com/sitemap.xml
curl -sI "https://serixa.yasaboy.com/?page=packages"
```

Optional env:

```bash
export SERIXA_SITE_URL=https://serixa.yasaboy.com
```

## Cloudflare Pages (static export)

Production is also deployable as a **static export** to Cloudflare Pages:

```bash
cd website
composer install
composer build
php bin/export-static.php
npx wrangler pages deploy dist --project-name=serixa
```

Custom domain: attach `serixa.yasaboy.com` in Pages → Custom domains, then add DNS:

| Type  | Name   | Target            | Proxy |
|-------|--------|-------------------|-------|
| CNAME | serixa | serixa.pages.dev  | Proxied |

Do **not** put `/?page=…` rules in `_redirects` — Pages ignores query strings when matching and will break `/`.

Optional env for build-time absolute URLs:

```bash
export SERIXA_SITE_URL=https://serixa.yasaboy.com
```

## Local

```bash
cd website
composer install
composer build
composer start
# http://localhost:8080
```
