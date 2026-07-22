# serixa/permissions

Permission catalog and matrix helpers on top of `serixa/rbac`.

## Install

```bash
cd packages/permissions && composer install && composer qa && composer smoke
```

## Usage

```php
use Serixa\Permissions\PermissionCatalog;
use Serixa\Rbac\Rbac;

$catalog = PermissionCatalog::make()
    ->group('users', 'Users', ['users.view', 'users.edit']);

$rbac = $catalog->syncTo(Rbac::memory());
$rows = $catalog->matrix(); // for admin DataTable
```

See [docs/permissions.md](docs/permissions.md) and [docs/architecture.md](docs/architecture.md).
