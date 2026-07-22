<?php

declare(strict_types=1);

namespace Serixa\Rbac;

use Serixa\Rbac\Contracts\Authorizable;
use Serixa\Rbac\Contracts\PermissionStore;
use Serixa\Rbac\Contracts\RoleStore;
use Serixa\Rbac\Memory\MemoryPermissionStore;
use Serixa\Rbac\Memory\MemoryRoleStore;

/**
 * Application entry point: wires stores and Gate.
 */
final class Rbac
{
    private Gate $gate;

    public function __construct(
        private readonly RoleStore $roleStore,
        private readonly PermissionStore $permissionStore,
    ) {
        $this->gate = new Gate($roleStore, $permissionStore);
    }

    public static function memory(): self
    {
        return new self(new MemoryRoleStore(), new MemoryPermissionStore());
    }

    public function gate(): Gate
    {
        return $this->gate;
    }

    public function roles(): RoleStore
    {
        return $this->roleStore;
    }

    public function permissions(): PermissionStore
    {
        return $this->permissionStore;
    }

    public function definePermission(string $name, ?string $label = null): Permission
    {
        $permission = Permission::make($name, $label);
        $this->permissionStore->put($permission);

        return $permission;
    }

    /**
     * @param list<string> $permissions
     */
    public function defineRole(string $name, ?string $label = null, array $permissions = []): Role
    {
        foreach ($permissions as $perm) {
            if (! $this->permissionStore->has($perm)) {
                $this->definePermission($perm);
            }
        }
        $role = Role::make($name, $label, $permissions);
        $this->roleStore->put($role);

        return $role;
    }

    public function assign(Authorizable|string $subject, string $role): self
    {
        $this->roleStore->assignRole($this->id($subject), $role);

        return $this;
    }

    public function grant(Authorizable|string $subject, string $permission): self
    {
        if (! $this->permissionStore->has($permission)) {
            $this->definePermission($permission);
        }
        $this->roleStore->grantPermission($this->id($subject), $permission);

        return $this;
    }

    public function can(Authorizable|string $subject, string $ability): bool
    {
        return $this->gate->can($subject, $ability);
    }

    public function cannot(Authorizable|string $subject, string $ability): bool
    {
        return $this->gate->cannot($subject, $ability);
    }

    public function authorize(Authorizable|string $subject, string $ability): void
    {
        $this->gate->authorize($subject, $ability);
    }

    private function id(Authorizable|string $subject): string
    {
        return $subject instanceof Authorizable
            ? $subject->getAuthorizationId()
            : $subject;
    }
}
