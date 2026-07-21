# Roadmap

Product vision: the easiest way to build modern websites with PHP only — Flutter-like widgets, beautiful defaults, zero Tailwind for common UI.

## Completed

### Milestone 1 — Rendering engine
- [x] Base component / widget engine
- [x] Renderer
- [x] Button, Card, Text, Container
- [x] Method chaining, escaping, PHPUnit

### Milestone 2 — Layout engine & theme
- [x] Row, Column, Center, Padding, Spacer, Expanded, SizedBox, Stack, Divider
- [x] ThemeInterface, DefaultTheme, ThemeManager
- [x] Fluent Button aliases (`primary()`, `large()`, …)

### Milestone 3 — Framework foundations (`v0.3.0`)
- [x] Widget vocabulary + Component BC aliases
- [x] WidgetTree, TreeWalker, lifecycle hooks
- [x] StateManager (memory-only)
- [x] Design tokens
- [x] Event interfaces (prep only)
- [x] AccessibilityBuilder

### Milestone 4 — Runtime Core (`v0.4.0`)
- [x] WidgetContext + RenderContext
- [x] Service Container
- [x] WidgetRegistry + WidgetMetadata
- [x] Render Pipeline + HtmlWriter
- [x] Plugin contracts + PluginManager
- [x] Inspector / Snapshot / Performance contracts

### Milestone 5 — Document & Asset Engine (`v0.5.0`)
- [x] Document, Html, Head, Body
- [x] Title, Meta, Link, Script, Style
- [x] SeoManager (OG, Twitter, JSON-LD placeholder)
- [x] AssetManager, AssetBundle, AssetManifest, AssetResolver
- [x] DocumentRenderer + Renderer document detection

### Milestone 5.1 — API Stewardship (`v0.5.1`)
- [x] Public API freeze / stability docs
- [x] Exception hierarchy
- [x] PHPStan + PHP-CS-Fixer + EditorConfig
- [x] GitHub Actions CI
- [x] ADRs, versioning & deprecation policies
- [x] `@internal` markers on non-SemVer types

### Milestone 5.2 — DX cleanup (`v0.5.2`)
- [x] Fluent Padding builder (`Padding::make()->x()->y()->child()`)
- [x] Structural `Scaffold` widget (appBar / sidebar / body / footer)
- [x] Canonical `Serixa\` namespace across the codebase
- [x] Namespace / architecture docs

### Milestone 6.0 — UI Foundation (`v0.6.0`)
- [x] AppBar, Sidebar, MainContent, Footer
- [x] Form, Label, Input, PasswordInput, Textarea, Checkbox, Radio, SwitchControl, Select
- [x] Link, FieldGroup, HelperText, ErrorText
- [x] Divider orientation / spacing improvements

### Milestone 7.0 — Data Display & Application Widgets (`v0.7.0`)
- [x] DataTable + Table primitives (convenience `headers()`/`rows()` + composition `child(Table)`)
- [x] SearchField, Pagination, Toolbar, PageHeader
- [x] Avatar, Image, Logo
- [x] StatCard, Metric, EmptyState, ActivityList / ActivityItem
- [x] Playground CRUD / Dashboard / Profile wired to real widgets

## Upcoming

### Milestone 7.1 — Polish & validation display
- [ ] Badge
- [ ] Field validation adapters (UI only)
- [ ] Chart placeholder contract / adapters

### Milestone 7 — Interactive widgets
- [ ] Modal, Tabs, Accordion, Drawer, Dropdown, Toast
- [ ] Minimal JS bridge driven by StateManager + events

### Milestone 8 — App DX
- [ ] `App::build` / page composition helpers
- [ ] Dark-mode toggle helpers (using WidgetContext colorMode)
- [ ] Plugin marketplace adapters (optional)

### Later
- [ ] Routing (optional adapter, not a full framework clone)
- [ ] Auth / ORM / CLI — out of core UI scope unless explicitly scheduled
- [ ] Vite / asset bundler adapters
