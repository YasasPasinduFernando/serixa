<?php

declare(strict_types=1);

namespace Serixa\Organization;

use Serixa\Organization\Contracts\OrganizationStore;
use Serixa\Organization\Memory\MemoryOrganizationStore;

final class Organizations
{
    public function __construct(private readonly OrganizationStore $store)
    {
    }

    public static function memory(): self
    {
        return new self(new MemoryOrganizationStore());
    }

    public function store(): OrganizationStore
    {
        return $this->store;
    }

    public function create(string $id, string $name, ?string $slug = null): Organization
    {
        $org = Organization::make($id, $name, $slug);
        $this->store->put($org);

        return $org;
    }

    public function addMember(string $organizationId, string $memberId, string $role = 'member'): Membership
    {
        $membership = Membership::make($organizationId, $memberId, $role);
        $this->store->addMember($membership);

        return $membership;
    }

    public function isMember(string $organizationId, string $memberId): bool
    {
        return $this->store->isMember($organizationId, $memberId);
    }
}
