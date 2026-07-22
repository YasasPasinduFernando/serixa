<?php

declare(strict_types=1);

namespace Serixa\Menu;

use Serixa\Exception\InvalidArgumentException;

final class MenuItem
{
    /** @var list<MenuItem> */
    private array $children = [];

    public function __construct(
        private readonly string $label,
        private readonly ?string $url = null,
        private readonly ?string $id = null,
    ) {
        if ($label === '') {
            throw new InvalidArgumentException('Menu item label is required.');
        }
    }

    public static function make(string $label, ?string $url = null, ?string $id = null): self
    {
        return new self($label, $url, $id);
    }

    public function label(): string
    {
        return $this->label;
    }

    public function url(): ?string
    {
        return $this->url;
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function add(MenuItem $child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * @return list<MenuItem>
     */
    public function children(): array
    {
        return $this->children;
    }

    public function hasChildren(): bool
    {
        return $this->children !== [];
    }

    /**
     * @return array{label: string, url: ?string, id: ?string, children: list<array<string, mixed>>}
     */
    public function toArray(): array
    {
        $children = [];
        foreach ($this->children as $child) {
            $children[] = $child->toArray();
        }

        return [
            'label' => $this->label,
            'url' => $this->url,
            'id' => $this->id,
            'children' => $children,
        ];
    }
}
