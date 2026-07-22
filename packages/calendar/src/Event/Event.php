<?php

declare(strict_types=1);

namespace Serixa\Calendar\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Serixa\Calendar\Support\DateMath;

/**
 * Calendar event value object — UI only; apps own persistence.
 */
final class Event
{
    private ?string $id = null;

    private string $title = '';

    private DateTimeImmutable $start;

    private ?DateTimeImmutable $end = null;

    private ?string $color = null;

    private ?string $url = null;

    private ?string $description = null;

    private bool $allDay = false;

    private ?string $resource = null;

    private function __construct()
    {
        $this->start = new DateTimeImmutable('today');
    }

    public static function make(?string $title = null): self
    {
        $event = new self();

        if ($title !== null) {
            $event->title = $title;
        }

        return $event;
    }

    public function id(?string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function start(DateTimeInterface|string $start): self
    {
        $this->start = DateMath::parse($start);

        return $this;
    }

    public function end(DateTimeInterface|string|null $end): self
    {
        $this->end = $end === null ? null : DateMath::parse($end);

        return $this;
    }

    public function color(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function url(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function description(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function allDay(bool $allDay = true): self
    {
        $this->allDay = $allDay;

        return $this;
    }

    public function resource(?string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStart(): DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): DateTimeImmutable
    {
        return $this->end ?? $this->start;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isAllDay(): bool
    {
        return $this->allDay;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function occursOn(DateTimeInterface $day): bool
    {
        $d = DateMath::startOfDay($day);
        $start = DateMath::startOfDay($this->start);
        $end = DateMath::startOfDay($this->getEnd());

        return $d >= $start && $d <= $end;
    }

    /**
     * @return array{
     *     id: ?string,
     *     title: string,
     *     start: string,
     *     end: string,
     *     color: ?string,
     *     url: ?string,
     *     description: ?string,
     *     allDay: bool,
     *     resource: ?string
     * }
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'start' => DateMath::isoDateTime($this->start),
            'end' => DateMath::isoDateTime($this->getEnd()),
            'color' => $this->color,
            'url' => $this->url,
            'description' => $this->description,
            'allDay' => $this->allDay,
            'resource' => $this->resource,
        ];
    }
}
