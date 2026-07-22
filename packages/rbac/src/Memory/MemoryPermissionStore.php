<?php

declare(strict_types=1);

namespace Serixa\Rbac\Memory;

use Serixa\Rbac\Contracts\PermissionStore;
use Serixa\Rbac\Permission;

final class MemoryPermissionStore implements PermissionStore
{
    /** @var array<string, Permission> */
    private array $items = [];

    public function put(Permission $permission): void
    {
        $this->items[$permission->name()] = $permission;
    }

    public function has(string $name): bool
    {
        return isset($this->items[$name]);
    }

    public function get(string $name): ?Permission
    {
        return $this->items[$name] ?? null;
    }

    public function all(): array
    {
        return array_values($this->items);
    }

    public function remove(string $name): void
    {
        unset($this->items[$name]);
    }
}
