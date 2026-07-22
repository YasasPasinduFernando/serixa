<?php

declare(strict_types=1);

namespace Serixa\Calendar\Support;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Serixa\Calendar\Exceptions\CalendarException;

/**
 * Calendar date helpers (grids, ranges, parsing).
 *
 * @internal
 */
final class DateMath
{
    private function __construct()
    {
    }

    public static function parse(DateTimeInterface|string $value): DateTimeImmutable
    {
        if ($value instanceof DateTimeImmutable) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        try {
            return new DateTimeImmutable($value);
        } catch (\Exception $e) {
            throw new CalendarException(sprintf('Cannot parse calendar date "%s".', $value), 0, $e);
        }
    }

    public static function startOfDay(DateTimeInterface $date): DateTimeImmutable
    {
        return self::parse($date)->setTime(0, 0, 0);
    }

    public static function endOfDay(DateTimeInterface $date): DateTimeImmutable
    {
        return self::parse($date)->setTime(23, 59, 59);
    }

    public static function startOfMonth(DateTimeInterface $date): DateTimeImmutable
    {
        return self::parse($date)->modify('first day of this month')->setTime(0, 0, 0);
    }

    public static function endOfMonth(DateTimeInterface $date): DateTimeImmutable
    {
        return self::parse($date)->modify('last day of this month')->setTime(23, 59, 59);
    }

    public static function startOfWeek(DateTimeInterface $date, int $weekStartsOn = 1): DateTimeImmutable
    {
        $day = self::startOfDay($date);
        $dow = (int) $day->format('N'); // 1=Mon … 7=Sun
        $diff = ($dow - $weekStartsOn + 7) % 7;

        return $day->sub(new DateInterval('P' . $diff . 'D'));
    }

    public static function endOfWeek(DateTimeInterface $date, int $weekStartsOn = 1): DateTimeImmutable
    {
        return self::startOfWeek($date, $weekStartsOn)->add(new DateInterval('P6D'))->setTime(23, 59, 59);
    }

    /**
     * Build a 6×7 month grid of dates (including leading/trailing days).
     *
     * @return list<list<DateTimeImmutable>>
     */
    public static function monthGrid(DateTimeInterface $focus, int $weekStartsOn = 1): array
    {
        $first = self::startOfMonth($focus);
        $cursor = self::startOfWeek($first, $weekStartsOn);
        $weeks = [];

        for ($w = 0; $w < 6; $w++) {
            $row = [];

            for ($d = 0; $d < 7; $d++) {
                $row[] = $cursor;
                $cursor = $cursor->add(new DateInterval('P1D'));
            }

            $weeks[] = $row;
        }

        return $weeks;
    }

    /**
     * @return list<DateTimeImmutable>
     */
    public static function weekDays(DateTimeInterface $focus, int $weekStartsOn = 1): array
    {
        $cursor = self::startOfWeek($focus, $weekStartsOn);
        $days = [];

        for ($i = 0; $i < 7; $i++) {
            $days[] = $cursor;
            $cursor = $cursor->add(new DateInterval('P1D'));
        }

        return $days;
    }

    /**
     * Weekday labels Mon–Sun (or Sun–Sat when weekStartsOn=7).
     *
     * @return list<string>
     */
    public static function weekdayLabels(int $weekStartsOn = 1, string $format = 'D'): array
    {
        $cursor = self::startOfWeek(new DateTimeImmutable('2024-01-01'), $weekStartsOn);
        $labels = [];

        for ($i = 0; $i < 7; $i++) {
            $labels[] = $cursor->format($format);
            $cursor = $cursor->add(new DateInterval('P1D'));
        }

        return $labels;
    }

    public static function sameDay(DateTimeInterface $a, DateTimeInterface $b): bool
    {
        return self::parse($a)->format('Y-m-d') === self::parse($b)->format('Y-m-d');
    }

    public static function isoDate(DateTimeInterface $date): string
    {
        return self::parse($date)->format('Y-m-d');
    }

    public static function isoDateTime(DateTimeInterface $date): string
    {
        return self::parse($date)->format('Y-m-d\TH:i:s');
    }
}
