# Decisions, incidents and follow-up

## Static docs hosting

The launch work added `website/bin/export-static.php`: render PHP locally, export HTML to `website/dist`, copy public assets and rewrite query-based routes to static paths. Cloudflare Pages hosts that output, not the PHP source tree. Use `website/DEPLOY.md` for commands. The exporter clears its output directory: do not store authored files in dist.

Historical incident: query-string rules in Pages `_redirects` made the homepage return a redirect stub, sometimes with HTTP 200. Avoid `/?page=...` redirect matching there. Verify the HTML body and expected title, not just a successful status. PHP SSR routes and exported path routes are different; test the intended hosting mode.

## Static themes

PHP query/cookie handling does not run at a static edge. `website/public/assets/runtime/theme-static.js` applies URL theme, then cookie, then light default, updates the shell and toggle links. The exporter injects the script. Verify both asset linkage and actual browser rendering; curl alone cannot establish visual correctness.

## DNS and release evidence

Historical custom-domain registration succeeded before its DNS CNAME existed. Pages domain attachment and zone DNS configuration are separate operations. OAuth permission for Pages does not imply DNS edit permission. Old NXDOMAIN and launch-success reports are historical; recheck authoritative DNS and HTTPS when diagnosing a new incident.

## Prioritized follow-up (not completed by this knowledge base)

- Expand CI beyond package/starter skeleton checks to meaningful supported integration coverage.
- Check each ecosystem package's release readiness and Composer constraints before public promotion.
- Keep roadmap, FAQ and package marketing consistent with verified implementation.
- Track actual PHP runtime patching separately from framework releases; see `../php-runtime-security.md`.
- Validate browser theme behavior and exported routes after website changes.

## Flagship docs presentation (2026-10-31)

The public homepage is a SERIXA-native showcase built from framework widgets rather than a separate frontend stack. Brand assets live under `website/public/assets/brand`; the source pack is not required at build time. `website/resources/css/app.css` is the maintained presentation source, and `website/public/assets/{app,serixa}.css` are generated outputs.

The visual system uses the supplied cyan/violet identity, a real widget-tree demonstration, responsive ecosystem/starter grids, and accessible light/dark modes. Preserve one `h1`, the main landmark, keyboard focus styles, reduced-motion behavior, and mobile horizontal navigation. Verify 390px, 768px and 1440px in both themes after layout changes. The static exporter writes Cloudflare Pages security/cache headers; review them when adding browser capabilities or third-party origins.
