# ADR 0001 — Runtime namespace remains `NovaPHP\`

## Status

Accepted

## Context

The product is branded **SERIXA** (`serixa/framework`), but early milestones shipped under the `NovaPHP\` PSR-4 namespace. Renaming now would break every consumer import.

## Decision

Keep `NovaPHP\` as the runtime namespace through `0.x`. Document SERIXA as the product name. A future major (`1.0` or dedicated major) may introduce `Serixa\` with aliases.

## Consequences

- Composer package name and docs say SERIXA; code says `NovaPHP\`.
- No silent rename of namespaces in minor releases.
