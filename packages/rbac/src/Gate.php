<?php

declare(strict_types=1);

namespace Serixa\Rbac;

use Serixa\Rbac\Contracts\Authorizable;
use Serixa\Rbac\Contracts\PermissionStore;
use Serixa\Rbac\Contracts\RoleStore;
use Serixa\Rbac\Exceptions\AuthorizationException;

/**
 * Evaluates abilities for subjects via roles and direct grants.
 */
final class Gate
{
    public function __construct(
        private readonly RoleStore $roles,
        private readonly PermissionStore $permissions,
    ) {
    }

    public function allow(Authorizable|string $subject, string $ability): bool
    {
        return $this->can($subject, $ability);
    }

    public function deny(Authorizable|string $subject, string $ability): bool
    {
        return $this->cannot($subject, $ability);
    }

    public function can(Authorizable|string $subject, string $ability): bool
    {
        $id = $this->id($subject);

        if (in_array($ability, $this->roles->directPermissionsFor($id), true)) {
            return true;
        }

        foreach ($this->roles->rolesFor($id) as $roleName) {
            $role = $this->roles->get($roleName);
            if ($role !== null && $role->hasPermission($ability)) {
                return true;
            }
        }

        return false;
    }

    public function cannot(Authorizable|string $subject, string $ability): bool
    {
        return ! $this->can($subject, $ability);
    }

    /**
     * @throws AuthorizationException
     */
    public function authorize(Authorizable|string $subject, string $ability): void
    {
        if ($this->cannot($subject, $ability)) {
            throw AuthorizationException::denied($ability, $this->id($subject));
        }
    }

    /**
     * Effective permission names for a subject (roles + direct).
     *
     * @return list<string>
     */
    public function abilities(Authorizable|string $subject): array
    {
        $id = $this->id($subject);
        $out = $this->roles->directPermissionsFor($id);

        foreach ($this->roles->rolesFor($id) as $roleName) {
            $role = $this->roles->get($roleName);
            if ($role === null) {
                continue;
            }
            foreach ($role->permissions() as $perm) {
                $out[] = $perm;
            }
        }

        return array_values(array_unique($out));
    }

    public function roles(): RoleStore
    {
        return $this->roles;
    }

    public function permissions(): PermissionStore
    {
        return $this->permissions;
    }

    private function id(Authorizable|string $subject): string
    {
        return $subject instanceof Authorizable
            ? $subject->getAuthorizationId()
            : $subject;
    }
}
