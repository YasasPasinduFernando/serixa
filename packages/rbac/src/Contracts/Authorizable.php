<?php

declare(strict_types=1);

namespace Serixa\Rbac\Contracts;

/**
 * Subject that can hold roles and permissions.
 */
interface Authorizable
{
    public function getAuthorizationId(): string;
}
