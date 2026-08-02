# SERIXA Public Launch Report

**Date:** 2026-08-02  
**Declaration:** SERIXA is publicly launched.

## Packagist verification

**PASS** — `composer require serixa/framework:v1.0.0` resolves on Packagist.

- Package: https://packagist.org/packages/serixa/framework

## Deployment verification

**PASS** — Documentation site is live over HTTPS.

- URL: https://serixa.yasaboy.com
- HTTP status: 200
- HTTPS / SSL: active (Cloudflare edge)

## Cloudflare verification

**PASS** — Cloudflare Pages project `serixa` is configured with the custom domain active.

- Project: `serixa`
- Custom domain: serixa.yasaboy.com (active)
- Pages preview host: `*.pages.dev` for the project

## DNS verification

**PASS** — A records for `serixa.yasaboy.com` resolve via Cloudflare proxy.

- Resolved A (proxied): 104.21.61.140 (and related CF anycast)

## Portfolio verification

**PASS** — Portfolio project page links documentation to the live docs site; “Coming Live” is gone.

- Portfolio: https://portfolio.yasaboy.com/projects/serixa/
- Documentation link: https://serixa.yasaboy.com

## Final launch checklist

| Item | Status |
|------|--------|
| Packagist `serixa/framework` v1.0.0 | PASS |
| Docs site HTTP 200 + HTTPS/SSL | PASS |
| Cloudflare Pages project `serixa` + domain | PASS |
| DNS A records via CF proxy | PASS |
| Portfolio docs link + no “Coming Live” | PASS |
| GitHub release v1.0.0 | PASS |
| Framework distro repo | PASS |

## Static export note

Docs are deployed as a **static export** to Cloudflare Pages. Dark/light theme on Pages uses `theme-static.js` (client-side theme handling without query-string `_redirects` that Pages cannot safely honor).

- Export tooling: `website/bin/export-static.php`
- Theme runtime: `website/public/assets/runtime/theme-static.js`
- Deploy notes: `website/DEPLOY.md`

## URLs

| Resource | URL |
|----------|-----|
| Docs | https://serixa.yasaboy.com |
| Packagist | https://packagist.org/packages/serixa/framework |
| GitHub | https://github.com/YasasPasinduFernando/serixa |
| Release | https://github.com/YasasPasinduFernando/serixa/releases/tag/v1.0.0 |
| Framework distro | https://github.com/YasasPasinduFernando/serixa-framework |
| Portfolio | https://portfolio.yasaboy.com/projects/serixa/

---

**SERIXA is publicly launched.**
