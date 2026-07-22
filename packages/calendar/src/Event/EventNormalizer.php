<?php

declare(strict_types=1);

namespace Serixa\Calendar\Event;

use DateTimeInterface;
use Generator;
use Serixa\Calendar\Exceptions\CalendarException;
use Traversable;

/**
 * Coerces heterogeneous inputs into a list of {@see Event}.
 *
 * @internal
 */
final class EventNormalizer
{
    private function __construct()
    {
    }

    /**
     * @param array<mixed>|Traversable<mixed>|string|Generator|Event|callable(): mixed $input
     * @return list<Event>
     */
    public static function normalize(mixed $input): array
    {
        if ($input instanceof Event) {
            return [$input];
        }

        if (is_callable($input) && ! is_array($input)) {
            /** @var mixed $resolved */
            $resolved = $input();

            return self::normalize($resolved);
        }

        $items = self::toList($input);
        $out = [];

        foreach ($items as $item) {
            $out[] = self::one($item);
        }

        return $out;
    }

    /**
     * @param array<mixed>|Traversable<mixed>|string|Generator $input
     * @return list<mixed>
     */
    public static function toList(mixed $input): array
    {
        if (is_string($input)) {
            $decoded = json_decode($input, true);

            if (! is_array($decoded)) {
                throw new CalendarException('Calendar JSON string must decode to an array.');
            }

            if (array_is_list($decoded)) {
                return $decoded;
            }

            return [$decoded];
        }

        if ($input instanceof Generator || $input instanceof Traversable) {
            return array_values(iterator_to_array($input, false));
        }

        if (is_array($input)) {
            return array_values($input);
        }

        if (is_object($input) && method_exists($input, 'toArray')) {
            /** @var mixed $arr */
            $arr = $input->toArray();

            if (! is_array($arr)) {
                throw new CalendarException('Object toArray() must return an array.');
            }

            return array_values($arr);
        }

        throw new CalendarException(sprintf(
            'Unsupported calendar events input of type %s.',
            get_debug_type($input),
        ));
    }

    public static function one(mixed $item): Event
    {
        if ($item instanceof Event) {
            return $item;
        }

        if (! is_array($item)) {
            throw new CalendarException(sprintf(
                'Cannot normalize calendar event of type %s.',
                get_debug_type($item),
            ));
        }

        $title = (string) ($item['title'] ?? $item['name'] ?? '');
        $start = $item['start'] ?? $item['from'] ?? null;

        if ($start === null) {
            throw new CalendarException('Calendar event requires a start date.');
        }

        if (! is_string($start) && ! $start instanceof DateTimeInterface) {
            throw new CalendarException('Calendar event start must be a string or DateTimeInterface.');
        }

        $event = Event::make($title)->start($start);

        if (isset($item['id'])) {
            $event->id((string) $item['id']);
        }

        if (isset($item['end']) || isset($item['to'])) {
            $end = $item['end'] ?? $item['to'];

            if ($end !== null && ! is_string($end) && ! $end instanceof DateTimeInterface) {
                throw new CalendarException('Calendar event end must be a string or DateTimeInterface.');
            }

            $event->end($end);
        }

        if (isset($item['color'])) {
            $event->color((string) $item['color']);
        }

        if (isset($item['url'])) {
            $event->url((string) $item['url']);
        }

        if (isset($item['description'])) {
            $event->description((string) $item['description']);
        }

        if (isset($item['allDay'])) {
            $event->allDay((bool) $item['allDay']);
        }

        if (isset($item['resource'])) {
            $event->resource((string) $item['resource']);
        }

        return $event;
    }

    /**
     * @param list<Event> $events
     * @return list<Event>
     */
    public static function occurringOn(array $events, DateTimeInterface $day): array
    {
        return array_values(array_filter(
            $events,
            static fn (Event $event): bool => $event->occursOn($day),
        ));
    }

    /**
     * @param list<Event> $events
     * @return list<Event>
     */
    public static function inRange(array $events, DateTimeInterface $from, DateTimeInterface $to): array
    {
        $fromTs = $from->getTimestamp();
        $toTs = $to->getTimestamp();

        return array_values(array_filter(
            $events,
            static function (Event $event) use ($fromTs, $toTs): bool {
                return $event->getEnd()->getTimestamp() >= $fromTs
                    && $event->getStart()->getTimestamp() <= $toTs;
            },
        ));
    }
}
