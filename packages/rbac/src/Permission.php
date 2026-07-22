<?php

declare(strict_types=1);

namespace Serixa\Rbac;

use Serixa\Exception\InvalidArgumentException;

/**
 * Named ability (e.g. users.view).
 */
final class Permission
{
    public function __construct(
        private readonly string $name,
        private readonly ?string $label = null,
    ) {
        if ($name === '') {
            throw new InvalidArgumentException('Permission name cannot be empty.');
        }
    }

    public static function make(string $name, ?string $label = null): self
    {
        return new self($name, $label);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function label(): string
    {
        return $this->label ?? $this->name;
    }
}
