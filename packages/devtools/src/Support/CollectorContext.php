<?php

declare(strict_types=1);

namespace Serixa\DevTools\Support;

use Serixa\Contracts\DocumentInterface;
use Serixa\Contracts\WidgetInterface;

/**
 * Shared context passed to collectors and inspectors during a DevTools pass.
 */
final class CollectorContext
{
    /**
     * @param array<string, mixed> $extras
     */
    public function __construct(
        private readonly ?WidgetInterface $widget = null,
        private readonly ?DocumentInterface $document = null,
        private readonly string $html = '',
        private readonly ?float $startedAt = null,
        private readonly array $extras = [],
    ) {
    }

    public function widget(): ?WidgetInterface
    {
        return $this->widget;
    }

    public function document(): ?DocumentInterface
    {
        return $this->document;
    }

    public function html(): string
    {
        return $this->html;
    }

    public function startedAt(): ?float
    {
        return $this->startedAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function extras(): array
    {
        return $this->extras;
    }

    /**
     * @param array<string, mixed> $extras
     */
    public function withExtras(array $extras): self
    {
        return new self(
            $this->widget,
            $this->document,
            $this->html,
            $this->startedAt,
            array_merge($this->extras, $extras),
        );
    }

    /**
     * @param array<string, mixed> $extras
     */
    public static function make(
        ?WidgetInterface $widget = null,
        ?DocumentInterface $document = null,
        string $html = '',
        ?float $startedAt = null,
        array $extras = [],
    ): self {
        return new self($widget, $document, $html, $startedAt, $extras);
    }
}
