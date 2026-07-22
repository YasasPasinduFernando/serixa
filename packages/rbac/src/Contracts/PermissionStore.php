<?php

declare(strict_types=1);

namespace Serixa\Rbac\Contracts;

use Serixa\Rbac\Permission;

interface PermissionStore
{
    public function put(Permission $permission): void;

    public function has(string $name): bool;

    public function get(string $name): ?Permission;

    /**
     * @return list<Permission>
     */
    public function all(): array;

    public function remove(string $name): void;
}
