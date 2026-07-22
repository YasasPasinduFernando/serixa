<?php

declare(strict_types=1);

namespace Serixa\DevTools\Contracts;

use Serixa\DevTools\Support\CollectorContext;

interface CollectorInterface
{
    public function name(): string;

    /**
     * @return array<string, mixed>
     */
    public function collect(CollectorContext $ctx): array;
}
