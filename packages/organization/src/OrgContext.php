<?php

declare(strict_types=1);

namespace Serixa\Organization;

use Serixa\Organization\Contracts\OrganizationStore;
use Serixa\Organization\Exceptions\OrganizationException;
use Serixa\Organization\Memory\MemoryOrganizationStore;

/**
 * Holds the current organization context for a request/app scope.
 */
final class OrgContext
{
    private ?string $currentId = null;

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

    public function setCurrent(?string $organizationId): self
    {
        if ($organizationId !== null && ! $this->store->has($organizationId)) {
            throw OrganizationException::notFound($organizationId);
        }
        $this->currentId = $organizationId;

        return $this;
    }

    public function currentId(): ?string
    {
        return $this->currentId;
    }

    public function current(): ?Organization
    {
        return $this->currentId === null ? null : $this->store->get($this->currentId);
    }

    public function requireCurrent(): Organization
    {
        $org = $this->current();
        if ($org === null) {
            throw OrganizationException::noContext();
        }

        return $org;
    }
}
