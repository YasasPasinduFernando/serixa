# FAQ

## Is SERIXA a full-stack framework like Laravel or Symfony?

No. SERIXA is a **PHP UI / SSR widget engine**. It renders HTML from PHP widgets and gives you a CLI to scaffold apps. It does not include a router, an ORM, an auth system, or a job queue. You bring those yourself, or use SERIXA widgets inside an existing framework's controllers/views.

## Does SERIXA have a router?

No. Generated apps use a plain `match ($_GET['page'])` in `public/index.php`. See [first-blog.md](first-blog.md#step-10--wire-your-own-routing-3-min) for the pattern, and [deployment.md](deployment.md#nginx) if you want path-based URLs instead of query strings. If you outgrow `match()`, use any routing library you like — SERIXA has no opinion here.

## Does SERIXA have authentication or sessions?

No. Login/Profile pages you see in examples are HTML only — no session checks, no password hashing, no CSRF tokens. Add your own session/auth layer (native PHP sessions, a library, or your existing framework's auth) and gate page rendering yourself.

## Are forms validated automatically?

**Core widgets:** `Form`, `Input`, `Textarea`, `Select`, etc. render real HTML with native constraints (`required`, `minlength`, `pattern`, …) and can carry a UI-only `invalid()` flag for styling. The **framework** does not read `$_POST` or run a validation engine by itself.

**Optional package:** [`serixa/forms`](forms.md) adds form builders, binding, and validators when you need server-side validation — still your app decides when to run them against request input.

## What's the difference between `Widget` and `Component`?

`Serixa\Widget\Widget` is the current abstract base class. `Serixa\Component\Component` is a backward-compatible alias kept for earlier code. Concrete widgets (`Button`, `Card`, …) live under the `Serixa\Component\` namespace either way — that's a namespace grouping, not a hint about which base class to prefer. Prefer `WidgetInterface` in type hints going forward.

## What's the difference between `StatCard` and `Metric`?

Use `StatCard` for a full KPI tile in a dashboard grid (title, big value, optional trend/icon/color, its own border and padding). Use `Metric` for a compact inline label/value pair inside other content (no card chrome). If you're building a stat grid, reach for `StatCard`; if you're labeling a number inside a table row or card, reach for `Metric`.

## Why is theme styling class names instead of a CSS file?

SERIXA themes map widget state to **class names**. `DefaultTheme` still emits utility class lists for broad coverage; the **Theme Compiler** also emits CSS variables and semantic `.sx-*` recipes (for example `.sx-btn-primary`).

At build time, `serixa build` (or `composer build` in the website) compiles offline CSS to `/assets/serixa.css`. There is **no** Tailwind Play CDN and **no** runtime CSS generation. See [styling.md](styling.md) and [assets.md](assets.md).

## Can I swap out the theme?

Yes — `serixa make:theme Dark` scaffolds a custom theme class implementing `ThemeInterface`. Pass it to `Renderer` (or your app's DI) in place of `DefaultTheme`. Unknown component/state combinations throw a clear `InvalidArgumentException` rather than silently rendering nothing — see [troubleshooting.md](troubleshooting.md#unknown-theme-state).

## Is there a Chart widget? A Calendar widget?

Yes — official packages `serixa/charts` and `serixa/calendar` ship chart and calendar UI widgets. See [charts.md](charts.md) and [calendar.md](calendar.md).

## Does SERIXA use a virtual DOM or hydration?

No. Every request renders a full HTML string server-side. The client runtime (`assets/runtime/serixa.js`) is a small progressive-enhancement script — it binds click handlers, focus traps for modals, keyboard navigation for tabs/dropdowns, etc. — but it never re-renders widgets or manages application state in the browser.

## Do I need the client runtime script at all?

Only if you use interactive widgets that need it: `Modal`, `Drawer`, `Dropdown`, `Popover`, `Tooltip`, `Tabs`, `Accordion`, `Toast`, or fluent DOM events (`onClick`, `onSubmit`, …). Static pages built from layout and content widgets (`Column`, `Card`, `Text`, `Form` without JS behavior) work with zero JavaScript.

## Can I use SERIXA widgets inside an existing PHP app (Laravel, Symfony, plain PHP)?

Yes. `Renderer::render()` returns a plain HTML string. Call it from a controller action, an existing template, or a CLI script and echo/return the result — SERIXA doesn't require owning your whole request lifecycle. Using it as a `Document` (full page) versus a widget fragment is your choice per call site.

## Why does `serixa make:page Foo` refuse to run twice?

`make:*` commands never overwrite existing files — you'll see `Refusing to overwrite existing file`. This is intentional, to avoid silently discarding edits. Rename the target, or delete the existing file first. See [troubleshooting.md](troubleshooting.md#duplicate-make-commands).

## Where do I report a bug or ask something not covered here?

Check [troubleshooting.md](troubleshooting.md) first, then the subsystem docs linked from the [README](../README.md#documentation). If you're still stuck, open an issue with a minimal reproduction.
