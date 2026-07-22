<?php

declare(strict_types=1);

namespace Serixa\MediaLibrary\Memory;

use Serixa\MediaLibrary\Contracts\MediaStore;
use Serixa\MediaLibrary\MediaItem;

final class MemoryMediaStore implements MediaStore
{
    /** @var array<string, MediaItem> */
    private array $items = [];

    public function put(MediaItem $item): void
    {
        $this->items[$item->id()] = $item;
    }

    public function get(string $id): ?MediaItem
    {
        return $this->items[$id] ?? null;
    }

    public function findByPath(string $path): ?MediaItem
    {
        foreach ($this->items as $item) {
            if ($item->path() === $path) {
                return $item;
            }
        }

        return null;
    }

    public function all(): array
    {
        return array_values($this->items);
    }

    public function remove(string $id): void
    {
        unset($this->items[$id]);
    }
}
