<?php

declare(strict_types=1);

namespace Serixa\DevTools\Contracts;

interface PanelInterface
{
    public function name(): string;

    public function label(): string;

    /**
     * @param array<string, mixed> $data
     */
    public function render(array $data): string;
}
