<?php

declare(strict_types=1);

namespace Serixa\Teams;

use Serixa\Organization\Contracts\OrganizationStore;
use Serixa\Teams\Contracts\TeamStore;
use Serixa\Teams\Exceptions\TeamException;
use Serixa\Teams\Memory\MemoryTeamStore;

/**
 * Teams scoped to organizations.
 */
final class Teams
{
    public function __construct(
        private readonly TeamStore $teams,
        private readonly OrganizationStore $organizations,
    ) {
    }

    public static function memory(OrganizationStore $organizations): self
    {
        return new self(new MemoryTeamStore(), $organizations);
    }

    public function store(): TeamStore
    {
        return $this->teams;
    }

    public function create(string $id, string $organizationId, string $name): Team
    {
        if (! $this->organizations->has($organizationId)) {
            throw TeamException::organizationRequired($organizationId);
        }
        $team = Team::make($id, $organizationId, $name);
        $this->teams->put($team);

        return $team;
    }

    public function addMember(string $teamId, string $memberId, string $role = 'member'): TeamMembership
    {
        if (! $this->teams->has($teamId)) {
            throw TeamException::notFound($teamId);
        }
        $membership = TeamMembership::make($teamId, $memberId, $role);
        $this->teams->addMember($membership);

        return $membership;
    }

    public function isMember(string $teamId, string $memberId): bool
    {
        return $this->teams->isMember($teamId, $memberId);
    }

    /**
     * @return list<Team>
     */
    public function forOrganization(string $organizationId): array
    {
        return $this->teams->forOrganization($organizationId);
    }
}
