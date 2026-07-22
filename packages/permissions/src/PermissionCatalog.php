<?php

declare(strict_types=1);

namespace Serixa\Permissions;

use Serixa\Rbac\Rbac;

/**
 * Registers grouped abilities and syncs them into an Rbac instance.
 */
final class PermissionCatalog
{
    /** @var array<string, PermissionGroup> */
    private array $groups = [];

    public static function make(): self
    {
        return new self();
    }

    public function register(PermissionGroup $group): self
    {
        $this->groups[$group->key()] = $group;

        return $this;
    }

    /**
     * @param list<string> $abilities
     */
    public function group(string $key, string $label, array $abilities): self
    {
        return $this->register(PermissionGroup::make($key, $label, $abilities));
    }

    public function has(string $key): bool
    {
        return isset($this->groups[$key]);
    }

    public function get(string $key): ?PermissionGroup
    {
        return $this->groups[$key] ?? null;
    }

    /**
     * @return list<PermissionGroup>
     */
    public function groups(): array
    {
        return array_values($this->groups);
    }

    /**
     * Flat list of all ability names.
     *
     * @return list<string>
     */
    public function allAbilities(): array
    {
        $out = [];
        foreach ($this->groups as $group) {
            foreach ($group->abilities() as $ability) {
                $out[] = $ability;
            }
        }

        return array_values(array_unique($out));
    }

    /**
     * Define every catalog permission on the given Rbac instance.
     */
    public function syncTo(Rbac $rbac): Rbac
    {
        foreach ($this->groups as $group) {
            foreach ($group->abilities() as $ability) {
                $rbac->definePermission($ability, $ability);
            }
        }

        return $rbac;
    }

    /**
     * Matrix rows for admin UI tables: group, ability, label.
     *
     * @return list<array{group: string, group_label: string, ability: string}>
     */
    public function matrix(): array
    {
        $rows = [];
        foreach ($this->groups as $group) {
            foreach ($group->abilities() as $ability) {
                $rows[] = [
                    'group' => $group->key(),
                    'group_label' => $group->label(),
                    'ability' => $ability,
                ];
            }
        }

        return $rows;
    }
}
