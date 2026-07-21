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

## Upcoming (untouched)

### Milestone 4 — Document & assets
- [ ] HTML document shell (`html` / `head` / `body`)
- [ ] Asset / Tailwind pipeline conventions
- [ ] Dark-mode toggle helpers

### Milestone 5 — Forms
- [ ] Input, Textarea, Select, Checkbox, Label
- [ ] Form layout helpers
- [ ] Validation display (UI only)

### Milestone 6 — Interactive widgets
- [ ] Modal, Tabs, Accordion, Drawer, Dropdown, Toast
- [ ] Minimal JS bridge driven by StateManager + events

### Milestone 7 — App DX
- [ ] `App::build` / page composition helpers
- [ ] Namespace rebrand path (`NovaPHP\` → `Serixa\`) if approved
- [ ] Plugin API

### Later
- [ ] Routing (optional adapter, not a full framework clone)
- [ ] Auth / ORM / CLI — out of core UI scope unless explicitly scheduled
