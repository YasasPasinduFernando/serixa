<?php

declare(strict_types=1);

namespace Serixa\Calendar\Event;

/**
 * Ordered list of calendar events with query helpers.
 */
final class EventCollection
{
    /** @var list<Event> */
    private array $events;

    /**
     * @param list<Event> $events
     */
    private function __construct(array $events)
    {
        $this->events = $events;
    }

    public static function make(mixed $input = []): self
    {
        return new self(EventNormalizer::normalize($input));
    }

    /**
     * @return list<Event>
     */
    public function all(): array
    {
        return $this->events;
    }

    public function count(): int
    {
        return count($this->events);
    }

    public function add(Event ...$events): self
    {
        foreach ($events as $event) {
            $this->events[] = $event;
        }

        return $this;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(static fn (Event $e): array => $e->toArray(), $this->events);
    }
}
