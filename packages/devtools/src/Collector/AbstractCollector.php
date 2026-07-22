<?php

declare(strict_types=1);

namespace Serixa\DevTools\Collector;

use Serixa\DevTools\Contracts\CollectorInterface;
use Serixa\DevTools\Support\CollectorContext;

abstract class AbstractCollector implements CollectorInterface
{
    public function __construct(
        private readonly string $name,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function collect(CollectorContext $ctx): array;
}
