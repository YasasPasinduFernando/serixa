<?php

declare(strict_types=1);

namespace Serixa\Organization;

use Serixa\Exception\InvalidArgumentException;

final class Organization
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly ?string $slug = null,
    ) {
        if ($id === '' || $name === '') {
            throw new InvalidArgumentException('Organization id and name are required.');
        }
    }

    public static function make(string $id, string $name, ?string $slug = null): self
    {
        return new self($id, $name, $slug);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function slug(): string
    {
        return $this->slug ?? $this->id;
    }
}
