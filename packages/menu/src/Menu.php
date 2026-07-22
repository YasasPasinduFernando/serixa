<?php

declare(strict_types=1);

namespace Serixa\Menu;

use Serixa\Exception\InvalidArgumentException;

final class Menu
{
    /** @var list<MenuItem> */
    private array $items = [];

    public function __construct(private readonly string $id)
    {
        if ($id === '') {
            throw new InvalidArgumentException('Menu id is required.');
        }
    }

    public static function make(string $id): self
    {
        return new self($id);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function add(MenuItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return list<MenuItem>
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function toArray(): array
    {
        $out = [];
        foreach ($this->items as $item) {
            $out[] = $item->toArray();
        }

        return $out;
    }
}
