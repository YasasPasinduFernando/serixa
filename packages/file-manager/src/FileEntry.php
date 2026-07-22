<?php

declare(strict_types=1);

namespace Serixa\FileManager;

final class FileEntry
{
    public function __construct(
        private readonly string $path,
        private readonly string $name,
        private readonly bool $directory = false,
        private readonly int $size = 0,
    ) {
    }

    public function path(): string
    {
        return $this->path;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isDirectory(): bool
    {
        return $this->directory;
    }

    public function size(): int
    {
        return $this->size;
    }
}
