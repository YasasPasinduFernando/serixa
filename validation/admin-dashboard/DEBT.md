# Admin dashboard validation — DEBT

Gaps hit while composing only public `Serixa\` APIs (no `src/` changes).

## Missing widgets

- **Chart** — Analytics / Dashboard “traffic” tab has no chart primitive; used `Skeleton` + copy as stand-in.
- **Breadcrumb / NavItem** — Sidebar uses plain `Link` list; no dedicated admin nav item with icons or nested sections.

## API / DX friction

- **Form / auth** — `Form` is render-only; login has no session, CSRF, or redirect helpers.
- **DataTable cells** — Mixing strings and widgets works, but there is no column/type helper for badges or actions.
- **Toast / Modal wiring** — Relies on manual `id` + `data-serixa-*` attributes; no fluent `Toast::for(Button)` binding.
- **Page routing** — App-owned `match` in `public/index.php`; framework has no router.

## Out of scope (not framework gaps)

- No persistence, search, pagination logic, or real bulk actions — static SSR fixtures only.
