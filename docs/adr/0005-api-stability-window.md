# ADR 0005 — Public API freeze before UI expansion

## Status

Accepted

## Context

Milestones 1–5 delivered rendering, layout, foundations, runtime, and documents. Before adding forms and interactive widgets, the public surface must be stable for open-source contributors.

## Decision

Treat the current public surface as **frozen for additive changes only** until the next minor that intentionally expands UI kits. Removals require deprecation (see DEPRECATION.md). Internals marked `@internal` are not covered by SemVer for BC.

## Consequences

- Contributors know what is safe to depend on.
- CI enforces tests + PHPStan + CS.
- New widgets must not break existing public APIs.
