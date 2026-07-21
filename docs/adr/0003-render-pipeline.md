# ADR 0003 — Renderer coordinates a replaceable pipeline

## Status

Accepted

## Context

Lifecycle, theme bridging, accessibility, and HTML writing needed extension points without bloating `Renderer`.

## Decision

`Renderer` is a coordinator. Default `RenderPipeline` runs ordered `RenderPassInterface` implementations. Passes marked `@internal` may change in minor versions; the pipeline interface is public.

## Consequences

- Apps can compose custom pipelines.
- Widget `render(): string` API stays unchanged.
- ThemeManager is bridged from WidgetContext during ThemeResolutionPass for BC.
