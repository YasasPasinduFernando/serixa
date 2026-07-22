<?php

declare(strict_types=1);

namespace Serixa\Permissions;

use Serixa\Exception\InvalidArgumentException;

/**
 * Named group of permission abilities (e.g. Users module).
 */
final class PermissionGroup
{
    /** @var list<string> */
    private array $abilities;

    /**
     * @param list<string> $abilities
     */
    public function __construct(
        private readonly string $key,
        private readonly string $label,
        array $abilities = [],
    ) {
        if ($key === '') {
            throw new InvalidArgumentException('Permission group key cannot be empty.');
        }
        $this->abilities = array_values(array_unique($abilities));
    }

    /**
     * @param list<string> $abilities
     */
    public static function make(string $key, string $label, array $abilities = []): self
    {
        return new self($key, $label, $abilities);
    }

    public function key(): string
    {
        return $this->key;
    }

    public function label(): string
    {
        return $this->label;
    }

    /**
     * @return list<string>
     */
    public function abilities(): array
    {
        return $this->abilities;
    }

    public function withAbility(string $ability): self
    {
        $abilities = $this->abilities;
        if (! in_array($ability, $abilities, true)) {
            $abilities[] = $ability;
        }

        return new self($this->key, $this->label, $abilities);
    }
}
