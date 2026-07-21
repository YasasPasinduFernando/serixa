# Changelog

All notable changes to the SERIXA Admin Starter are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [0.1.0] - 2026-07-22

### Added

- Initial release of the official SERIXA Admin Starter: a full administration
  panel scaffold built entirely on `serixa/framework`, `serixa/admin` and
  `serixa/icons`, with no database, ORM, or authentication logic.
- Sixteen pages: Dashboard, Users, Roles, Permissions, Departments, Products,
  Orders, Customers, Invoices, Reports, Analytics, Notifications, Audit Logs,
  Settings, Profile, and a 404 Not Found page.
- `App\Data\Fixtures` static fake data used across every page.
- `App\PanelLayout` shared shell (sidebar, top bar, footer) built on
  `Serixa\Admin\Layout\AdminLayout` / AppShell.
- `App\Web` query-string page router and `public/index.php` front
  controller, including a real `404` status code for unknown pages.
- Dashboard with metric cards, revenue summary, recent users/orders, activity
  feed, and quick actions.
- Table chrome on list pages: SearchToolbar, TableFilters, ColumnVisibility,
  BulkActions, RowActions, and framework DataTable.
- Smoke tests covering every page route.
