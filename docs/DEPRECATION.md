# Deprecation policy

## Goals

Allow evolution without surprising consumers.

## Rules

1. **Do not remove** a public API in the same release it is deprecated.
2. Mark deprecations with `@deprecated` in PHPDoc and list them under **Deprecated** in `CHANGELOG.md`.
3. Prefer aliases (`Component` → `Widget`) over hard removals.
4. Minimum window on `0.x`: **one minor release** between deprecation and removal.
5. From `1.0.0`: **one major** between deprecation and removal unless security requires otherwise.
6. Removals must include a migration note in CHANGELOG and docs.

## Current deprecations

| API | Prefer | Since |
|-----|--------|-------|
| `NovaPHP\Component\Component` | `NovaPHP\Widget\Widget` | v0.3.0 |
| `NovaPHP\Contracts\ComponentInterface` | `NovaPHP\Contracts\WidgetInterface` | v0.3.0 |
| `Renderer::renderComponent()` | `Renderer::renderWidget()` | v0.3.0 |

These remain supported; no removal is scheduled before `1.0.0` planning.
