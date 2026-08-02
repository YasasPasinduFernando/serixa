# Packagist Publication Report

**Date:** 2026-08-02  
**Status:** **Ready for one-click Packagist submit** (API token not available in CI agent)

## Distro repository (created)

| Item | Value |
|------|--------|
| GitHub | https://github.com/YasasPasinduFernando/serixa-framework |
| Contents | `framework/` subtree as repository root |
| Tag | `v1.0.0` |
| Composer name | `serixa/framework` |

Monorepo `YasasPasinduFernando/serixa` remains the development home. The distro repo exists **only** so Packagist can read a root `composer.json`.

## Current Packagist state

`serixa/framework` → **404** until you submit.

## Maintainer action (required — ~1 minute)

1. Open https://packagist.org/packages/submit  
2. Repository URL: `https://github.com/YasasPasinduFernando/serixa-framework`  
3. Submit / sync  
4. Enable GitHub webhook / Packagist GitHub App for auto-updates  
5. Verify:

```bash
composer clear-cache
composer require serixa/framework:^1.0
```

6. Reply here when Packagist shows the package so we can re-verify install and update README badges.

## Not publishing yet

Ecosystem packages and `serixa/cli` (still path/`*@dev` in monorepo). Publish those only after framework install is proven and constraints are SemVer.
