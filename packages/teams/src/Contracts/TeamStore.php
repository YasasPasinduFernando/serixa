<?php

declare(strict_types=1);

namespace Serixa\Teams\Contracts;

use Serixa\Teams\Team;
use Serixa\Teams\TeamMembership;

interface TeamStore
{
    public function put(Team $team): void;

    public function get(string $id): ?Team;

    public function has(string $id): bool;

    /**
     * @return list<Team>
     */
    public function forOrganization(string $organizationId): array;

    public function remove(string $id): void;

    public function addMember(TeamMembership $membership): void;

    public function removeMember(string $teamId, string $memberId): void;

    public function isMember(string $teamId, string $memberId): bool;

    /**
     * @return list<TeamMembership>
     */
    public function membersOf(string $teamId): array;
}
