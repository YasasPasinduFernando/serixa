# Data display & application widgets (v0.7.0)

Status: **complete**.

Render-only widgets for dashboards and CRUD surfaces. No routing, search logic, or JavaScript.

## Architecture

```text
DataTable  →  wraps Table / thead / tbody / tr / th / td
Toolbar    →  leading | center | trailing slots
PageHeader →  title + subtitle + actions
StatCard / Metric / ActivityList / EmptyState → dashboard blocks
Avatar / Image / Logo → media
SearchField / Pagination → CRUD chrome (markup only)
```

All widgets resolve Tailwind via `ThemeInterface`, register in `WidgetRegistry`, and compose through the existing render pipeline.

## DataTable

```php
DataTable::make()
    ->headers(['Name', 'Email', 'Actions'])
    ->rows([
        ['Ada', 'ada@example.com', Link::make('Edit')->href('/edit')],
    ])
    ->striped()
    ->hover()
    ->emptyState(EmptyState::make()->title('No rows'));
```

Low-level primitives: `Table`, `TableHead`, `TableBody`, `TableRow`, `TableCell`, `TableHeaderCell`.

## Pagination

```php
Pagination::make()
    ->currentPage(2)
    ->totalPages(10)
    ->urlForPage(fn (int $page): string => '/?p=' . $page);
```

## Accessibility

| Widget | Notes |
|--------|-------|
| DataTable | Responsive region + optional caption |
| Pagination | `nav` + `aria-current="page"` |
| SearchField | `role="search"` / `searchbox` |
| Avatar | `role="img"` + `aria-label` |
| ActivityList | `role="list"` / listitem |

## Out of scope

Charts, Badge, LinkField icon rows, validation engines, client-side search/sort.
