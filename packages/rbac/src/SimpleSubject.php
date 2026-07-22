<?php

declare(strict_types=1);

namespace Serixa\Rbac;

use Serixa\Rbac\Contracts\Authorizable;

/**
 * Simple subject for demos and tests.
 */
final class SimpleSubject implements Authorizable
{
    public function __construct(private readonly string $id)
    {
    }

    public static function make(string $id): self
    {
        return new self($id);
    }

    public function getAuthorizationId(): string
    {
        return $this->id;
    }
}
