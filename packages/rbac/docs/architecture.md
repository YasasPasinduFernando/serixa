# serixa/rbac Architecture

## Purpose

Framework-agnostic RBAC for SERIXA apps: define permissions and roles, assign them to subjects, evaluate with `Gate`.

## Non-goals

- Sessions, HTTP middleware, password hashing
- ORM / database drivers (apps implement `PermissionStore` / `RoleStore`)
- UI widgets (use `serixa/admin` tables in the host app)

## Components

| Type | Role |
|------|------|
| `Permission` / `Role` | Value objects |
| `Authorizable` | Subject contract (`getAuthorizationId()`) |
| `PermissionStore` / `RoleStore` | Persistence ports |
| `Memory*` | In-memory drivers for tests and demos |
| `Gate` | `can` / `cannot` / `authorize` / `abilities` |
| `Rbac` | Facade wiring stores + convenience `define*` / `assign` / `grant` |

## Evaluation order

1. Direct subject permissions
2. Permissions inherited from assigned roles

Unknown abilities simply return `false` (not an error). Missing role definitions on assign are allowed; evaluation skips missing roles.

## Dependency

`serixa/framework` for shared `InvalidArgumentException` / `SerixaException` only.
