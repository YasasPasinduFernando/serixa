<?php

declare(strict_types=1);

namespace Serixa\Organization\Memory;

use Serixa\Organization\Contracts\OrganizationStore;
use Serixa\Organization\Membership;
use Serixa\Organization\Organization;

final class MemoryOrganizationStore implements OrganizationStore
{
    /** @var array<string, Organization> */
    private array $orgs = [];

    /** @var array<string, Membership> keyed by orgId|memberId */
    private array $memberships = [];

    public function put(Organization $organization): void
    {
        $this->orgs[$organization->id()] = $organization;
    }

    public function get(string $id): ?Organization
    {
        return $this->orgs[$id] ?? null;
    }

    public function has(string $id): bool
    {
        return isset($this->orgs[$id]);
    }

    public function all(): array
    {
        return array_values($this->orgs);
    }

    public function remove(string $id): void
    {
        unset($this->orgs[$id]);
        foreach (array_keys($this->memberships) as $key) {
            if (str_starts_with($key, $id . '|')) {
                unset($this->memberships[$key]);
            }
        }
    }

    public function addMember(Membership $membership): void
    {
        $key = $membership->organizationId() . '|' . $membership->memberId();
        $this->memberships[$key] = $membership;
    }

    public function removeMember(string $organizationId, string $memberId): void
    {
        unset($this->memberships[$organizationId . '|' . $memberId]);
    }

    public function isMember(string $organizationId, string $memberId): bool
    {
        return isset($this->memberships[$organizationId . '|' . $memberId]);
    }

    public function membersOf(string $organizationId): array
    {
        $out = [];
        foreach ($this->memberships as $membership) {
            if ($membership->organizationId() === $organizationId) {
                $out[] = $membership;
            }
        }

        return $out;
    }

    public function membershipsFor(string $memberId): array
    {
        $out = [];
        foreach ($this->memberships as $membership) {
            if ($membership->memberId() === $memberId) {
                $out[] = $membership;
            }
        }

        return $out;
    }
}
