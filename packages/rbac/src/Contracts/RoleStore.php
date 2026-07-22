<?php

declare(strict_types=1);

namespace Serixa\Rbac\Contracts;

use Serixa\Rbac\Role;

interface RoleStore
{
    public function put(Role $role): void;

    public function has(string $name): bool;

    public function get(string $name): ?Role;

    /**
     * @return list<Role>
     */
    public function all(): array;

    public function remove(string $name): void;

    public function assignRole(string $subjectId, string $roleName): void;

    public function revokeRole(string $subjectId, string $roleName): void;

    /**
     * @return list<string>
     */
    public function rolesFor(string $subjectId): array;

    public function grantPermission(string $subjectId, string $permissionName): void;

    public function revokePermission(string $subjectId, string $permissionName): void;

    /**
     * @return list<string>
     */
    public function directPermissionsFor(string $subjectId): array;
}
