# Permissions

Catalog layer for namespaced abilities and UI matrices.

## Sync

`PermissionCatalog::syncTo(Rbac)` defines every ability on the RBAC permission store.

## Matrix

`matrix()` returns rows `{group, group_label, ability}` for admin tables.
