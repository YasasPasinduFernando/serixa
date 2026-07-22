<?php

declare(strict_types=1);

namespace Serixa\Blog;

use Serixa\Exception\InvalidArgumentException;

final class Category
{
    public function __construct(
        private readonly string $id,
        private readonly string $slug,
        private readonly string $name,
    ) {
        if ($id === '' || $slug === '' || $name === '') {
            throw new InvalidArgumentException('Category id, slug, and name are required.');
        }
    }

    public static function make(string $id, string $slug, string $name): self
    {
        return new self($id, $slug, $name);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function name(): string
    {
        return $this->name;
    }
}
