<?php

declare(strict_types=1);

namespace Serixa\Forms\State;

final class OldInput
{
    /** @var array<string, mixed> */
    private array $input;

    /**
     * @param array<string, mixed> $input
     */
    private function __construct(array $input = [])
    {
        $this->input = $input;
    }

    /**
     * @param array<string, mixed> $input
     */
    public static function make(array $input = []): self
    {
        return new self($input);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return array_key_exists($key, $this->input) ? $this->input[$key] : $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->input);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->input;
    }

    public function put(string $key, mixed $value): self
    {
        $this->input[$key] = $value;

        return $this;
    }
}
