<?php

declare(strict_types=1);

namespace Serixa\Subscriptions\Exceptions;

use Serixa\Exception\SerixaException;

final class SubscriptionException extends SerixaException
{
    public static function alreadyCanceled(string $id): self
    {
        return new self(sprintf('Subscription [%s] is already canceled.', $id));
    }

    public static function planNotFound(string $id): self
    {
        return new self(sprintf('Plan [%s] was not found.', $id));
    }
}
