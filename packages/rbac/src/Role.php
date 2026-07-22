<?php

declare(strict_types=1);

namespace Serixa\Rbac;

use Serixa\Exception\InvalidArgumentException;

/**
 * Named role with attached permission names.
 */
final class Role
{
    /** @var list<string> */
    private array $permissions;

    /**
     * @param list<string> $permissions
     */
    public function __construct(
        private readonly string $name,
        private readonly ?string $label = null,
        array $permissions = [],
    ) {
        if ($name === '') {
            throw new InvalidArgumentException('Role name cannot be empty.');
        }
        $this->permissions = array_values(array_unique($permissions));
    }

    /**
     * @param list<string> $permissions
     */
    public static function make(string $name, ?string $label = null, array $permissions = []): self
    {
        return new self($name, $label, $permissions);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function label(): string
    {
        return $this->label ?? $this->name;
    }

    /**
     * @return list<string>
     */
    public function permissions(): array
    {
        return $this->permissions;
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions, true);
    }

    public function withPermission(string $permission): self
    {
        $perms = $this->permissions;
        if (! in_array($permission, $perms, true)) {
            $perms[] = $permission;
        }

        return new self($this->name, $this->label, $perms);
    }

    public function withoutPermission(string $permission): self
    {
        return new self(
            $this->name,
            $this->label,
            array_values(array_filter(
                $this->permissions,
                static fn (string $p): bool => $p !== $permission,
            )),
        );
    }
}
