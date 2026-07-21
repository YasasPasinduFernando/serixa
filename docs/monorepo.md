# SERIXA monorepo

This document explains how the SERIXA ecosystem is organized in a single Git repository, and how it can split into independent Composer packages later **without** refactoring public APIs or namespaces.

## Why a monorepo

- **One clone** for framework + packages + starters + docs + CI.
- **Atomic changes** across framework and dependent skeletons while packages are immature.
- **Path repositories** give Packagist-free local installs.
- **Split-ready layout**: each package already looks like a future standalone repository.

The production Composer package remains **`serixa/framework`** (`framework/`). Everything else depends on it.

## Directory map

| Path | Role |
|------|------|
| `framework/` | Core UI framework (`Serixa\`) — runtime only |
| `packages/cli` | Official CLI (`serixa/cli`) — scaffolding & DX |
| `packages/*` | Optional packages (icons, admin, charts, …) |
| `starters/*` | Starter kit skeletons |
| `playground/` | Manual demos |
| `tools/` | Build, release, benchmark, scripts |
| `docs/` | Ecosystem + framework documentation |
| `validation/` | Real-app validation fixtures from pre-1.0 audits |
| `.github/` | CI workflows |

## Composer path repositories

Local development uses Composer `path` repositories — not Packagist.

**Root** (`composer.json`):

- Declares path repos for `framework`, `packages/*`, `starters/*`.
- Scripts (`test`, `qa`, …) **delegate** to `framework/` via `--working-dir=framework`.
- Does **not** replace `framework/composer.json`.

**Packages / starters**:

```json
"repositories": [
  {
    "type": "path",
    "url": "../../framework",
    "options": { "symlink": true }
  }
],
"require": {
  "serixa/framework": "^0.11"
}
```

### Path-repo warning

Avoid generating nested apps *inside* `framework/` with path repos that point back at the framework tree — Composer junctions can recurse through nested `vendor/` folders. Prefer siblings: `packages/`, `starters/`, or directories outside the clone. See [troubleshooting.md](troubleshooting.md#path-repository-recursion).

## Package lifecycle

1. **Skeleton** — `composer.json`, README, LICENSE, CHANGELOG, empty `src/` / `tests/` / `docs/` / CI stub.
2. **Design** — public API proposal + ADR if needed (still no merge of unstable APIs without review).
3. **Implement** — code + tests + docs inside the package folder.
4. **Version** — package SemVer independent of framework when published (while staying compatible with a declared `serixa/framework` constraint).
5. **Split (optional)** — move folder to its own Git repo; publish; switch consumers from path → Packagist.

## Versioning strategy

| Artifact | Versioning |
|----------|------------|
| `serixa/framework` | SemVer in `framework/CHANGELOG.md` + git tags (`v0.11.0`, …) |
| Monorepo chore (layout only) | Patch tags such as `v0.11.1` may annotate repo structure without changing framework APIs |
| Packages | Independent SemVer once implemented; `Unreleased` until first release |
| Starters | Not published as libraries; versioned with the monorepo until extracted |

**Public framework APIs stay frozen** across this restructure: namespaces, widget contracts, and CLI command names do not change because of folder moves.

## Future split strategy

For `packages/charts` (example):

1. `git subtree split` / history filter / fresh repo with copied tree — team choice.
2. Ensure `composer.json` name remains `serixa/charts`.
3. Publish to Packagist.
4. In this monorepo (or consumers), replace path URL with `"serixa/charts": "^1.0"`.
5. Keep CI matrix jobs that already key off `packages/charts/**` paths.

Same pattern for `framework/` itself if the ecosystem repo later becomes docs-only.

## Migration strategy (this restructure)

| Before | After |
|--------|-------|
| `/src` | `/framework/src` |
| `/tests` | `/framework/tests` |
| `/bin` | `/framework/bin` |
| Root `composer.json` = framework | Root = meta; `framework/composer.json` = package |
| `composer qa` at root | Root delegates to `framework/` |

Application code using `Serixa\` via Composer continues to work: only the package root path changed inside this monorepo.

## Contributor workflow

```bash
# Clone once
git clone <repo> SERIXA && cd SERIXA

# Install framework deps (also triggered by root post-install)
composer install

# Day-to-day framework work
cd framework
composer qa
php bin/serixa version

# Or from root
composer qa
```

- Framework PRs: change `framework/**`, keep APIs stable, update `framework/CHANGELOG.md`.
- Package PRs: change only `packages/{name}/**` until implementation is scheduled.
- Starter PRs: structure and docs only until content milestones.
- Do not push unless asked; tags are local release markers.

## CI strategy

Root workflow runs **framework** QA on every PR.

Path-filtered workflows under each `packages/*/ .github/workflows/` (and root jobs for packages/starters) are prepared so packages and starters can gain real test jobs **independently** later — without publishing to Packagist yet.
