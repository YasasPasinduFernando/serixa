<?php

declare(strict_types=1);

namespace Serixa\GoogleCalendar;

use Serixa\GoogleCalendar\Exceptions\CalendarException;

final class CalendarEvent
{
    public function __construct(
        public readonly string $title,
        public readonly string $start,
        public readonly string $end,
        public readonly string $description = '',
        public readonly string $id = '',
    ) {
        if (trim($title) === '') {
            throw CalendarException::emptyTitle();
        }
        if ($end < $start) {
            throw CalendarException::invalidRange($start, $end);
        }
    }

    public function withId(string $id): self
    {
        return new self($this->title, $this->start, $this->end, $this->description, $id);
    }

    /**
     * @return array{id: string, title: string, start: string, end: string, description: string}
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
            'description' => $this->description,
        ];
    }

    /**
     * @param array{id?: string, title: string, start: string, end: string, description?: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['title'],
            (string) $data['start'],
            (string) $data['end'],
            (string) ($data['description'] ?? ''),
            (string) ($data['id'] ?? ''),
        );
    }
}
