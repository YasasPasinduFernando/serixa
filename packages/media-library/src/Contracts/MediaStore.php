<?php

declare(strict_types=1);

namespace Serixa\MediaLibrary\Contracts;

use Serixa\MediaLibrary\MediaItem;

interface MediaStore
{
    public function put(MediaItem $item): void;

    public function get(string $id): ?MediaItem;

    public function findByPath(string $path): ?MediaItem;

    /**
     * @return list<MediaItem>
     */
    public function all(): array;

    public function remove(string $id): void;
}
