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

### Milestone 8.0 — Progressive Enhancement Foundation (`v0.8.0`)
- [x] Fluent DOM events (`onClick`, `onChange`, `onInput`, `onSubmit`, …) → `data-serixa-on-*`
- [x] HTML validation attributes (`min`/`max`/`minLength`/`maxLength`/`pattern`/`readonly`/…)
- [x] Tiny client runtime `assets/runtime/serixa.js` (no VDOM / hydration / routing)
- [x] `EventRegistry` + `ClientRuntime` registration helpers
- [x] Playground Login / CRUD event metadata demos

### Milestone 8.1 — Stability patch (`v0.8.1`)
- [x] Checkbox / Radio / SwitchControl / SearchField attribute isolation

### Milestone 9.0 — Interactive Components (`v0.9.0`)
- [x] Modal, Drawer, Popover, Tooltip
- [x] Toast, Alert, Badge, Chip, Spinner, Progress, Skeleton
- [x] Dropdown / DropdownMenu / DropdownItem
- [x] Accordion / AccordionItem
- [x] Tabs / Tab / TabPanel
- [x] Modular runtime modules + WAI-ARIA patterns
- [x] Playground Interactive page demos

### Milestone 10.0 — Developer Experience / CLI (`v0.10.0`)
- [x] `bin/serixa` + `CommandRegistry` / `Application`
- [x] `new`, `make:*`, `docs`, `build`, `dev`
- [x] Filesystem templates + project presets (blog/crm/ecommerce)
- [x] Plugin command registration facade

## Upcoming

### Milestone 7.1 — Polish & validation display
- [ ] Chart placeholder contract / adapters
- [ ] Field validation adapters (UI only)

### Milestone 9.1 — Interactive polish
- [ ] Focus trap hardening / stacked overlays API
- [ ] Toast queue / portal helpers

### Milestone 10.1 — CLI polish
- [ ] Browser live-reload hook for `serixa dev`
- [ ] Installable community template packages

### Milestone 11 — Ecosystem monorepo (`v0.11.1`)
- [x] Move production package to `framework/`
- [x] Package skeletons (`packages/*`)
- [x] Starter skeletons (`starters/*`)
- [x] Root meta Composer + CI matrix for framework / packages / starters
- [x] `docs/monorepo.md`

### Milestone 12 — Extract CLI (`serixa/cli` v0.1.0 / framework v0.12.0)
- [x] Move Console + templates to `packages/cli`
- [x] Framework runtime-only (no `bin/`, no scaffolding)
- [x] Namespace `Serixa\Cli\`

### Milestone 7 — Interactive widgets (historical)
- [x] Modal, Tabs, Accordion, Drawer, Dropdown, Toast (delivered in v0.9.0)

### Milestone 8 — App DX
- [ ] `App::build` / page composition helpers
- [ ] Dark-mode toggle helpers (using WidgetContext colorMode)
- [ ] Plugin marketplace adapters (optional)

### Later
- [ ] Routing (optional adapter, not a full framework clone)
- [ ] Auth / ORM / CLI marketplace — out of core UI scope unless explicitly scheduled
- [ ] Vite / asset bundler adapters
