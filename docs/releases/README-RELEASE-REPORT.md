# README Release Report

**Date:** 2026-08-02  
**Scope:** Production README rewrite for public launch  
**Status:** Complete (local)

## Changes

Rewrote root `README.md` to launch quality:

- Flutter-inspired positioning (explicitly not Laravel / SPA / template engine)
- Why SERIXA exists
- Widget tree + rendering pipeline
- Badges: CI, PHP 8.2+, MIT, docs URL
- Installation + quick start + Hello World
- Honest package tiers (Core / UI kits / Dev tools / Official / Experimental)
- Starter status (blog/admin/crm **full**; ecommerce/portfolio/docs **scaffold**)
- Architecture, roadmap, contributing, security, license
- Live docs link: https://serixa.yasaboy.com

## Honesty notes baked in

- Packagist install documented with “until published, use path repo”
- Experimental adapters labeled (client ports + Fake*)
- No screenshot/GIF section — **none exist in the repo** (would be dishonest to invent)

## Related fixes

- `docs/faq.md` — forms answer mentions `serixa/forms`
- `CODE_OF_CONDUCT.md` added
- Issue + PR templates added
- `framework/composer.json` docs URL → serixa.yasaboy.com
