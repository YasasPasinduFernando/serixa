<?php

declare(strict_types=1);

namespace Serixa\Calendar\View;

/**
 * Named calendar presentation modes.
 */
final class CalendarView
{
    public const MONTH = 'month';

    public const WEEK = 'week';

    public const DAY = 'day';

    public const AGENDA = 'agenda';

    /** @var list<string> */
    public const ALL = [self::MONTH, self::WEEK, self::DAY, self::AGENDA];

    private function __construct()
    {
    }

    public static function isValid(string $view): bool
    {
        return in_array($view, self::ALL, true);
    }
}
