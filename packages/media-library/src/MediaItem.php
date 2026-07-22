<?php

declare(strict_types=1);

namespace Serixa\MediaLibrary;

use DateTimeImmutable;
use Serixa\Exception\InvalidArgumentException;

final class MediaItem
{
    public function __construct(
        private readonly string $id,
        private readonly string $path,
        private readonly string $name,
        private readonly string $mimeType = 'application/octet-stream',
        private readonly int $size = 0,
        private readonly ?DateTimeImmutable $createdAt = null,
    ) {
        if ($id === '' || $path === '' || $name === '') {
            throw new InvalidArgumentException('Media item id, path, and name are required.');
        }
    }

    public static function make(
        string $id,
        string $path,
        string $name,
        string $mimeType = 'application/octet-stream',
        int $size = 0,
        ?DateTimeImmutable $createdAt = null,
    ): self {
        return new self($id, $path, $name, $mimeType, $size, $createdAt);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function mimeType(): string
    {
        return $this->mimeType;
    }

    public function size(): int
    {
        return $this->size;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt ?? new DateTimeImmutable();
    }
}
