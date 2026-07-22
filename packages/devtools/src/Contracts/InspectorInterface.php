<?php

declare(strict_types=1);

namespace Serixa\DevTools\Contracts;

interface InspectorInterface
{
    public function name(): string;

    /**
     * @return array<string, mixed>
     */
    public function inspect(mixed $subject): array;
}
