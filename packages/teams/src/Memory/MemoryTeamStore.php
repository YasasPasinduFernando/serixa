<?php

declare(strict_types=1);

namespace Serixa\Teams\Memory;

use Serixa\Teams\Contracts\TeamStore;
use Serixa\Teams\Team;
use Serixa\Teams\TeamMembership;

final class MemoryTeamStore implements TeamStore
{
    /** @var array<string, Team> */
    private array $teams = [];

    /** @var array<string, TeamMembership> */
    private array $memberships = [];

    public function put(Team $team): void
    {
        $this->teams[$team->id()] = $team;
    }

    public function get(string $id): ?Team
    {
        return $this->teams[$id] ?? null;
    }

    public function has(string $id): bool
    {
        return isset($this->teams[$id]);
    }

    public function forOrganization(string $organizationId): array
    {
        $out = [];
        foreach ($this->teams as $team) {
            if ($team->organizationId() === $organizationId) {
                $out[] = $team;
            }
        }

        return $out;
    }

    public function remove(string $id): void
    {
        unset($this->teams[$id]);
        foreach (array_keys($this->memberships) as $key) {
            if (str_starts_with($key, $id . '|')) {
                unset($this->memberships[$key]);
            }
        }
    }

    public function addMember(TeamMembership $membership): void
    {
        $this->memberships[$membership->teamId() . '|' . $membership->memberId()] = $membership;
    }

    public function removeMember(string $teamId, string $memberId): void
    {
        unset($this->memberships[$teamId . '|' . $memberId]);
    }

    public function isMember(string $teamId, string $memberId): bool
    {
        return isset($this->memberships[$teamId . '|' . $memberId]);
    }

    public function membersOf(string $teamId): array
    {
        $out = [];
        foreach ($this->memberships as $membership) {
            if ($membership->teamId() === $teamId) {
                $out[] = $membership;
            }
        }

        return $out;
    }
}
