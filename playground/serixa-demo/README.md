# SERIXA Demo Playground

DX validation app for the local **SERIXA** framework (Milestones 1–5).

It installs `serixa/framework` via a Composer **path** repository and builds pages only with public SERIXA widgets. Missing UI is shown as explicit placeholders — the framework is **not** modified.

## Setup

From this directory:

```bash
composer install
php -S localhost:8000 -t public
```

Open:

- http://localhost:8000/?page=login
- http://localhost:8000/?page=dashboard
- http://localhost:8000/?page=crud
- http://localhost:8000/?page=settings
- http://localhost:8000/?page=profile

Optional render check (no server):

```bash
php smoke.php
```

## Layout

```text
serixa-demo/
  app/           # DocumentFactory, Shell, Missing helpers
  pages/         # Login, Dashboard, CRUD, Settings, Profile
  public/        # Front controller + served assets
  assets/        # Source CSS (mirrored under public/assets)
  composer.json  # path repo → ../../ (framework root)
  API_REVIEW.md  # DX findings
  README.md
  index.php      # CLI hint / fallback bootstrap
```

## Rules followed

- Consume SERIXA like an application (`use Serixa\...`).
- Do not add framework widgets from this playground.
- Record every missing widget and awkward API in [API_REVIEW.md](API_REVIEW.md).
