# Changelog

## [0.1.0] — 2026-07-22

### Added
- Official `serixa/admin` package: server-rendered admin UI kit with no routing, auth, ORM or JavaScript
- `Serixa\Admin\Theme\AdminTheme` decorating any host `ThemeInterface`, plus `AdminThemeMap` and the
  `ResolvesAdminTheme` trait
- Layout widgets: `AppShell`, `AdminLayout`, `Sidebar`, `SidebarGroup`, `SidebarItem`, `TopBar`, `FooterBar`,
  `MainContent`, `PageContainer`
- Page widgets: `Page`, `PageHeader`, `PageTitle`, `PageDescription`, `PageActions`
- Breadcrumb widgets: `Breadcrumb`, `BreadcrumbItem`
- Toolbar widgets: `Toolbar`, `ToolbarGroup`, `ToolbarAction`
- Card widgets: `MetricCard`, `MetricGrid`, `InfoCard`, `Statistic`
- Component widgets: `StatusBadge`, `AvatarGroup`, `UserChip`, `DescriptionList`, `DescriptionItem`
- `UserMenu` (SSR disclosure via native `<details>`/`<summary>`, no JavaScript)
- `EmptyState` (admin-styled wrapper around the framework `EmptyState`)
- Table widgets: `TableToolbar`, `SearchToolbar`, `TableFilters`, `BulkActions`, `RowActions`,
  `ColumnVisibility`, `ExportButton`, `ImportButton`
- All icons rendered via `Serixa\Icons\Component\Icon` — no inline SVG
- Examples: `dashboard.php`, `users.php`, `settings.php`, `reports.php`
- Documentation (`docs/admin.md`) and PHPUnit test suite
