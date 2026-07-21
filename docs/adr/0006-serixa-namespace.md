# ADR 0006 — Public namespace is `Serixa\`

## Status

Accepted (supersedes [ADR 0001](0001-runtime-namespace.md))

## Context

The product and Composer package are branded SERIXA. The public PHP namespace must match that identity for contributor and consumer DX.

## Decision

1. Use `Serixa\` as the sole PSR-4 root.
2. Do not maintain a second public namespace.
3. All examples, tests, and docs use `Serixa\`.

## Consequences

- Imports are `use Serixa\…` everywhere.
- No dual-namespace mental model for new contributors.
