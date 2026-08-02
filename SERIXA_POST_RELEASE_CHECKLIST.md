# SERIXA Post-Release Checklist

Use after Packagist + live docs are green.

- [ ] `composer require serixa/framework:^1.0` works on a clean machine
- [ ] Packagist badge added to README
- [ ] `https://serixa.yasaboy.com` → HTTP 200
- [ ] `/robots.txt`, `/sitemap.xml`, `/favicon.svg` → 200
- [ ] `/?page=packages` lists tiers including Experimental
- [ ] HTTPS / HSTS / no mixed content
- [ ] Lighthouse pass (Performance / A11y / Best Practices / SEO targets)
- [ ] Portfolio `cover.svg` / copy: remove “Documentation · Coming Soon”; link https://serixa.yasaboy.com
- [ ] ATS CV / Recruiter CV / Ask Yasas chatbot / search index — replace Coming Soon
- [ ] Enable Packagist GitHub auto-update webhook
- [ ] Sync subtree: when framework changes, re-run `git subtree split` → push `serixa-framework`
- [ ] Optional: publish `serixa/cli` with `^1.0` framework constraint
