# Blog validation — DEBT

Gaps hit while composing only public `Serixa\` APIs (no `src/` changes).

## Missing / out of scope

- **No router** — `public/index.php` uses `match` on `?page=`.
- **No auth/session** — Login/Profile are SSR UI only.
- **No search backend** — Search UI is presentational.
- **CLI leftover** — Generated `PostsPage` / `AppLayout` unused after custom pages/layouts.

## Framework-level (not blog-specific)

- Path-repository junction recursion when the app lives under the framework tree — see `validation/REPORT.md` Critical C1.
