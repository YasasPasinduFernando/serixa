<?php

declare(strict_types=1);

namespace Serixa\Rbac\Memory;

use Serixa\Rbac\Contracts\RoleStore;
use Serixa\Rbac\Role;

final class MemoryRoleStore implements RoleStore
{
    /** @var array<string, Role> */
    private array $roles = [];

    /** @var array<string, list<string>> */
    private array $subjectRoles = [];

    /** @var array<string, list<string>> */
    private array $subjectPermissions = [];

    public function put(Role $role): void
    {
        $this->roles[$role->name()] = $role;
    }

    public function has(string $name): bool
    {
        return isset($this->roles[$name]);
    }

    public function get(string $name): ?Role
    {
        return $this->roles[$name] ?? null;
    }

    public function all(): array
    {
        return array_values($this->roles);
    }

    public function remove(string $name): void
    {
        unset($this->roles[$name]);
        foreach ($this->subjectRoles as $subjectId => $roles) {
            $this->subjectRoles[$subjectId] = array_values(array_filter(
                $roles,
                static fn (string $r): bool => $r !== $name,
            ));
        }
    }

    public function assignRole(string $subjectId, string $roleName): void
    {
        $current = $this->subjectRoles[$subjectId] ?? [];
        if (! in_array($roleName, $current, true)) {
            $current[] = $roleName;
        }
        $this->subjectRoles[$subjectId] = $current;
    }

    public function revokeRole(string $subjectId, string $roleName): void
    {
        $current = $this->subjectRoles[$subjectId] ?? [];
        $this->subjectRoles[$subjectId] = array_values(array_filter(
            $current,
            static fn (string $r): bool => $r !== $roleName,
        ));
    }

    public function rolesFor(string $subjectId): array
    {
        return $this->subjectRoles[$subjectId] ?? [];
    }

    public function grantPermission(string $subjectId, string $permissionName): void
    {
        $current = $this->subjectPermissions[$subjectId] ?? [];
        if (! in_array($permissionName, $current, true)) {
            $current[] = $permissionName;
        }
        $this->subjectPermissions[$subjectId] = $current;
    }

    public function revokePermission(string $subjectId, string $permissionName): void
    {
        $current = $this->subjectPermissions[$subjectId] ?? [];
        $this->subjectPermissions[$subjectId] = array_values(array_filter(
            $current,
            static fn (string $p): bool => $p !== $permissionName,
        ));
    }

    public function directPermissionsFor(string $subjectId): array
    {
        return $this->subjectPermissions[$subjectId] ?? [];
    }
}
