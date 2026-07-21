<?php

declare(strict_types=1);

namespace NovaPHP\Event;

use NovaPHP\Contracts\EventInterface;
use NovaPHP\Contracts\WidgetInterface;

/**
 * In-memory dispatcher stub for tests and future JS bridge.
 */
final class NullWidgetEventDispatcher implements WidgetEventDispatcher
{
    /** @var array<string, list<callable(EventInterface): void>> */
    private array $listeners = [];

    public function listen(string $eventName, callable $listener): void
    {
        $this->listeners[$eventName][] = $listener;
    }

    public function dispatch(EventInterface $event, ?WidgetInterface $widget = null): void
    {
        // $widget reserved for future listener context (browser bridge).
        unset($widget);

        foreach ($this->listeners[$event->name()] ?? [] as $listener) {
            $listener($event);
        }
    }
}
