# Accessibility — SERIXA v1

## Principles

- Prefer native HTML elements (`button`, `a`, `label`, `input`).
- Use `AccessibilityBuilder` on widgets for ARIA when semantics need enrichment.
- Keyboard: interactive modules (modal, drawer, tabs, dropdown) are handled by `serixa.js`.
- Focus: Theme Compiler emits `:focus-visible` outlines for `.sx-btn` and `.sx-input`.

## Pipeline

`AccessibilityPass` records tree inventory stats on `RenderContext` metadata:

- `pipeline.accessibility.stats.buttons|inputs|textareas|selects|checkboxes|nodes`

No HTML is mutated by the pass (widgets remain the source of truth).

## Checklist for app authors

1. Associate every `Input` / `Select` / `Textarea` with a `Label` (or `aria-label`).
2. Ensure icon-only buttons have accessible names.
3. Test keyboard paths for Modal / Drawer / Dropdown / Tabs.
4. Verify contrast in both light and dark themes.
5. Respect `prefers-reduced-motion` in app CSS when adding custom animation.

## Known gaps (tracked)

- Automated contrast auditing
- Skip-link helper widget
- Live region helper for toasts beyond PE module
