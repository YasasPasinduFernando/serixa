# Coding standards

## PHP

- PHP 8.2+
- `declare(strict_types=1);` in every PHP file
- PSR-12 via PHP-CS-Fixer (`.php-cs-fixer.dist.php`)
- PHPStan level 6 (`phpstan.neon.dist`)

## Style

- Short array syntax
- Ordered imports
- No unused imports
- Fluent APIs return `static` / `self` as appropriate
- Prefer composition over inheritance

## Documentation

- Public classes and public methods require PHPDoc where types are not obvious
- Mark non-SemVer types with `@internal`
- Use `@deprecated` with a replacement pointer

## Tests

- PHPUnit 11
- New public behavior needs tests
- Do not reduce coverage when changing internals

## Commands

```bash
composer test
composer phpstan
composer cs-check
composer cs-fix
composer qa
```
