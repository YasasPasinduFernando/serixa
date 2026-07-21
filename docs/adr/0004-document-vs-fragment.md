# ADR 0004 — Documents and fragments are separate render paths

## Status

Accepted

## Context

SERIXA must support both HTML fragments (partials, HTMX) and full HTML documents (SSR pages).

## Decision

- Widgets → fragment pipeline.
- `DocumentInterface` → `DocumentRenderer` (Html/Head/Body + SEO + Assets).
- `Renderer::render()` accepts both; detection is type-based.

## Consequences

- Fragment callers never pay for document shell overhead.
- SEO and assets stay out of widget classes.
- SSR can wrap DocumentRenderer output.
