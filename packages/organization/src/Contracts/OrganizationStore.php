<?php

declare(strict_types=1);

namespace Serixa\Organization\Contracts;

use Serixa\Organization\Membership;
use Serixa\Organization\Organization;

interface OrganizationStore
{
    public function put(Organization $organization): void;

    public function get(string $id): ?Organization;

    public function has(string $id): bool;

    /**
     * @return list<Organization>
     */
    public function all(): array;

    public function remove(string $id): void;

    public function addMember(Membership $membership): void;

    public function removeMember(string $organizationId, string $memberId): void;

    public function isMember(string $organizationId, string $memberId): bool;

    /**
     * @return list<Membership>
     */
    public function membersOf(string $organizationId): array;

    /**
     * @return list<Membership>
     */
    public function membershipsFor(string $memberId): array;
}
