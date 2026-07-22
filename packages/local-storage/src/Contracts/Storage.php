<?php

declare(strict_types=1);

namespace Serixa\LocalStorage\Contracts;

/**
 * Shared object-storage contract for local and remote adapters.
 */
interface Storage
{
    /**
     * @param array<string, mixed> $options
     */
    public function put(string $path, string $contents, array $options = []): void;

    public function get(string $path): string;

    public function delete(string $path): void;

    public function exists(string $path): bool;

    public function url(string $path): string;
}
