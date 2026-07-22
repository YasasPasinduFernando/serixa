<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Contracts\CollectorInterface;
use Serixa\DevTools\Support\CollectorContext;

/**
 * Lazy registry of data collectors keyed by name.
 */
final class CollectorRegistry
{
    /** @var array<string, CollectorInterface> */
    private array $collectors = [];

    public function register(CollectorInterface $collector): void
    {
        $this->collectors[$collector->name()] = $collector;
    }

    /**
     * @return array<string, CollectorInterface>
     */
    public function all(): array
    {
        ksort($this->collectors);

        return $this->collectors;
    }

    /**
     * Run every registered collector. Only invoked when explicitly called (lazy).
     *
     * @return array<string, array<string, mixed>>
     */
    public function collectAll(CollectorContext $ctx): array
    {
        $results = [];

        foreach ($this->all() as $name => $collector) {
            $results[$name] = $collector->collect($ctx);
        }

        return $results;
    }
}
