<?php

declare(strict_types=1);

namespace Serixa\GoogleCalendar\Exceptions;

use RuntimeException;

final class CalendarException extends RuntimeException
{
    public static function emptyTitle(): self
    {
        return new self('Calendar event title must not be empty.');
    }

    public static function invalidRange(string $start, string $end): self
    {
        return new self(sprintf('Invalid event range: %s → %s.', $start, $end));
    }

    public static function notFound(string $id): self
    {
        return new self(sprintf('Calendar event "%s" not found.', $id));
    }
}
