# RBAC

Authorization primitives for SERIXA.

## Concepts

- **Permission** — named ability (`users.view`)
- **Role** — named set of permissions
- **Subject** — anything implementing `Authorizable`, or a string id
- **Gate** — evaluates access

## Defining catalog

```php
$rbac = Rbac::memory();
$rbac->definePermission('invoices.pay');
$rbac->defineRole('finance', 'Finance', ['invoices.pay', 'invoices.view']);
```

## Assigning

```php
$rbac->assign($user, 'finance');
$rbac->grant($user, 'reports.export'); // direct grant
```

## Checking

```php
$rbac->can($user, 'invoices.pay');
$rbac->cannot($user, 'admin.access');
$rbac->authorize($user, 'invoices.pay');
$rbac->gate()->abilities($user);
```

## Persistence

Implement `PermissionStore` and `RoleStore` for your database. Use `MemoryPermissionStore` / `MemoryRoleStore` for tests.

## Related

- `serixa/permissions` — permission catalog / groups on top of this package
- `docs/ecosystem-roadmap.md` — Phase 1 enterprise plan
