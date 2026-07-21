# ADR 0002 — Widget is canonical; Component is BC alias

## Status

Accepted

## Context

Milestone 1 used `Component` / `ComponentInterface`. Milestone 3 introduced Flutter-aligned `Widget` / `WidgetInterface`.

## Decision

- Canonical types: `Widget`, `WidgetInterface`.
- `Component` extends `Widget` and implements `ComponentInterface`.
- `ComponentInterface` extends `WidgetInterface`.
- Both remain public and supported for the deprecation window.

## Consequences

- New code should prefer Widget vocabulary.
- Existing apps using Component continue to work without changes.
