# Changelog

All notable changes to `serixa/charts` are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [0.1.0] - 2026-07-22

### Added

- Initial release of the official SERIXA Charts package (UI only).
- Chart types: Line, Bar, Area, Pie, Donut, Radar, Polar, Scatter, Bubble, Sparkline, ProgressRing, Gauge, Timeline, Heatmap.
- Dataset API: `Dataset`, `Series`, `Point`, `Normalizer`, `Arrayable` — arrays, Traversable, JSON, generators.
- Shared pieces: Axis / XAxis / YAxis, Grid, Legend, Tooltip, Marker, Threshold, Annotation.
- `ChartsTheme` decorator and `charts.*` theme keys (no Tailwind inside widgets).
- SSR figure + SVG + accessible table fallback; progressive enhancement via `assets/charts.js` and `ChartRuntime` (no hydration / VDOM).
- Examples: dashboard, analytics, sales, visitors, revenue, CRM, ecommerce.
- Documentation in `docs/charts.md`.
