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

## Upcoming

### Milestone 5 — Document & assets
- [ ] HTML document shell (`html` / `head` / `body`)
- [ ] Asset / Tailwind pipeline conventions
- [ ] Dark-mode toggle helpers (using WidgetContext colorMode)

### Milestone 6 — Forms
- [ ] Input, Textarea, Select, Checkbox, Label
- [ ] Form layout helpers
- [ ] Validation display (UI only)

### Milestone 7 — Interactive widgets
- [ ] Modal, Tabs, Accordion, Drawer, Dropdown, Toast
- [ ] Minimal JS bridge driven by StateManager + events

### Milestone 8 — App DX
- [ ] `App::build` / page composition helpers
- [ ] Namespace rebrand path (`NovaPHP\` → `Serixa\`) if approved
- [ ] Plugin marketplace adapters (optional)

### Later
- [ ] Routing (optional adapter, not a full framework clone)
- [ ] Auth / ORM / CLI — out of core UI scope unless explicitly scheduled
