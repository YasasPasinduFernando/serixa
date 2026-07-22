# serixa/rbac

Role-based access control for SERIXA applications. Logic only — no sessions, ORM, or UI.

## Install (monorepo)

```bash
cd packages/rbac
composer install
composer qa
composer smoke
```

## Quick start

```php
use Serixa\Rbac\Rbac;
use Serixa\Rbac\SimpleSubject;

$rbac = Rbac::memory();
$rbac->defineRole('editor', 'Editor', ['posts.edit', 'posts.view']);
$user = SimpleSubject::make('42');
$rbac->assign($user, 'editor');

if ($rbac->can($user, 'posts.edit')) {
    // …
}

$rbac->authorize($user, 'posts.edit'); // throws AuthorizationException if denied
```

## Docs

- [Architecture](docs/architecture.md)
- [User guide](docs/rbac.md)

## License

MIT
